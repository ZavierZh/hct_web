#include<stdio.h>

#define strcase(a,b) strncmp(a,b,sizeof(a)-1)


int main(int argc, const char *argv[])
{
	char *path="start_build:xxxxx";
	char str2[] = "start_build:123;abcdefghidk";
	printf("size:%d\n",(int)sizeof("start_build:"));
	//if(strncmp("start_build:",path,sizeof("start_build:")-1) == 0){
	if(strcase("start_build:",path) == 0){
		puts("ok");
	}else{
		puts("no");
	}
	//int fd=0;
	//fd = atoi(str2+12);
	//printf("fd:%d\n",fd);

	char * tmpbuff = str2 + 12;

	while (*tmpbuff != ';' && *tmpbuff != '\0' ){
		putchar(*tmpbuff);
		tmpbuff++;
	}
	puts("\n----------");
	puts(++tmpbuff);
	
	int i=0;
	for (;i<10;i++){
		printf("cur_i:%d\n",i);
		sleep(1);
	}



	return 0;
}
