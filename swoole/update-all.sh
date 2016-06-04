readonly HCT_CONST_BRANCH_DEVELOP=('develop' 'patch');
readonly HCT_CONST_BRANCH_DEFAULT='release'

function hct_code_cleanup_tmp() {
    if [ -e "${HCT_CONST_LOG_BUILD}" ]; then
        hct_set_environment

        hct_print_green_string "Now, cleaning code...\n"

        #  删除一些产生的临时文件
        for m_tmp in ${ENV_HCT_BUILD_PROJECT_NAME} ${ENV_HCT_BUILD_PROJECT_NAME}_${HCT_CONST_FOLDER_SIGNROM} ${HCT_CONST_DIR_PROJECT_CUSTOM}; do
            m_run_cmd="rm -rf ${m_tmp}"
            hct_print_green_string "${m_run_cmd}"
            ${m_run_cmd}
        done
    fi

    for m_tmp in *.log *.xml *.ini out; do
        m_run_cmd="rm -rf ${m_tmp}"
        hct_print_green_string "${m_run_cmd}"
        ${m_run_cmd}
    done

    #  将已修改文件还原
    m_run_cmd="./repo forall -c git checkout -f HEAD"
    hct_print_hints "${m_run_cmd}"
    ${m_run_cmd}

    #  清理不受 git 控管的文件
    m_run_cmd="./repo forall -c git clean -df"
    hct_print_hints "${m_run_cmd}"
    ${m_run_cmd}

    #  代码同步
    m_run_cmd="./repo sync"
    hct_print_hints "${m_run_cmd}"
    ${m_run_cmd}

    while [ "1" == "$?" ]
    do
        hct_print_green_string "Notice: ./repo sync, run agin..."
        ./repo sync
    done
}
function hct_code_start_branch() {
    m_branch_name=${1}

    test -z "${m_branch_name}" && hct_print_errors "Line: ${LINENO} Branch Name is NULL in hct_code_start_branch"

    GLOBAL_HCT_ALL_REPO_LIST=$(./repo list | sed 's/ .*//g' | xargs)

    m_run_cmd="./repo start ${m_branch_name} --all"
    hct_print_hints "${m_run_cmd}"
    ${m_run_cmd}
}


function hct_code_download() {
    m_branch_name=${1}

    test -z "${m_branch_name}" && hct_print_errors "Line: ${LINENO} Branch Name is NULL in hct_code_download"

    for m_repo_name in ${GLOBAL_HCT_ALL_REPO_LIST}; do
        m_run_cmd="./repo download ${m_repo_name} -m"

        hct_print_hints "${m_run_cmd} << Current branch is ${m_branch_name}"
        ${m_run_cmd}
    done

    m_run_cmd="./repo manifest -r -o ${m_branch_name}.xml"
    hct_print_hints "${m_run_cmd}"
    ${m_run_cmd}
}

function hct_code_merge() {
    m_branch_name=${1}

    test -z "${m_branch_name}" && hct_print_errors "Line: ${LINENO} Branch Name is NULL in hct_code_merge"

    for m_repo_name in ${GLOBAL_HCT_ALL_REPO_LIST[@]}; do
        m_run_cmd="./repo download ${m_repo_name} --br ${m_branch_name} -m"

        hct_print_hints "${m_run_cmd} << ${m_branch_name} merge to ${HCT_CONST_BRANCH_DEFAULT}"
        ${m_run_cmd}
    done
}


function hct_code_result_check() {
    test -z "${1}" && hct_print_errors "Line: ${LINENO} args is NULL in hct_code_result_check"

    m_repo_number=`echo ${GLOBAL_HCT_ALL_REPO_LIST} | xargs | wc -w`

    echo
    ./repo branch
    echo
}



    elif [ "${1}" == "${HCT_CONST_FLAG_CODE_UPDATE}" -a "${2}" == "${HCT_CONST_FLAG_CODE_ALL_BRANCH}" ]; then

        hct_code_cleanup_tmp

        for m_branch_name in ${HCT_CONST_BRANCH_DEVELOP[@]}; do
            hct_code_start_branch ${m_branch_name}
            hct_code_download ${m_branch_name}
        done

        hct_code_start_branch ${HCT_CONST_BRANCH_DEFAULT}
        hct_code_download ${HCT_CONST_BRANCH_DEFAULT}

        for m_branch_name in ${HCT_CONST_BRANCH_DEVELOP[@]}; do
            hct_code_merge ${m_branch_name}
        done

        hct_code_result_check ${HCT_CONST_BRANCH_DEFAULT} ${HCT_CONST_BRANCH_DEVELOP[@]}