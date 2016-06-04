#include<stdio.h>
#include<sys/types.h>
#include<sys/socket.h>
#include<netinet/in.h>
#include<arpa/inet.h>
#include<string.h>
#include<stdlib.h>
#include<fcntl.h>
#include<errno.h>
#include<sys/select.h>
#include<unistd.h>

#define strcase(a,b) 	strncmp(a,b,sizeof(a)-1)
#define strwrite(a) 	write(1,a,sizeof(a)-1)
#define strsend(a,b) 	send(a,b,sizeof(b)-1,0)

#define BUFF_SIZE  			1024
#define PATH_MIN_LEN 		20
#define ECHO_OUT 0

#define STAT_START 1
#define STAT_WAIT  2
#define STAT_RUN   3

#define STAT_CLOSE 8
#define STAT_failed 9

char *path="./data";


int main(int argc, const char *argv[]){
	unsigned int tcp_socket;
	unsigned char buff[BUFF_SIZE] = {0};
	unsigned char cur_path[BUFF_SIZE] = {0};
	struct sockaddr_in myaddr,peeraddr;
	unsigned int peer_len;
	unsigned int len;
	unsigned int fw;
	// 打开管道 ..
 	if(mkfifo(path,0664) == -1){
		if(errno == EEXIST){
			fw = open(path,O_RDONLY);	
		}else {
			perror("error:mkfifo");
			exit(1);
		}
	}else{
		fw = open(path,O_RDONLY);
	}
	strwrite("start-run\n");
	fputs("----*\n",stderr);
	// 设置服务器ip 和 port
	memset(&myaddr,0,sizeof(myaddr));
	myaddr.sin_family = PF_INET;
	// ip
	if((len = read(fw,buff,BUFF_SIZE)) <= 0){
		fprintf(stderr,"error:read fialed(len.%d)\n",len);
		goto error0;		
	}
	buff[len] = '\0';
	// fprintf(stderr,"error:IP set failed:%s\n",buff);
	len = inet_addr(buff);
	if( INADDR_NONE == len){ //INADDR_NONE (usually  -1)
		fprintf(stderr,"error:SERVER_IP set failed:%s\n",buff);
		goto error0;
	}
	fprintf(stderr,"SERVER_IP set ok:%s\n",buff);
	myaddr.sin_addr.s_addr = len;
	// port
	if((len = read(fw,buff,BUFF_SIZE)) <= 0){
		fprintf(stderr,"error:read fialed(len.%d)\n",len);
		goto error0;		
	}
	buff[len] = '\0';
	len = strtoul(buff,NULL,0);
	if (ERANGE == errno){ //ERANGE EINVAL
		fprintf(stderr,"error:SERVER_PORT set fialed:%s\n",buff);
		goto error0;
	}
	fprintf(stderr,"SERVER_PORT set ok:%s\n",buff);
	myaddr.sin_port = htons(len);

	// pc-name
	if((len = read(fw,buff,BUFF_SIZE)) <= 0){
		fprintf(stderr,"error:read fialed(len.%d)\n",len);
		goto error0;		
	}
	buff[len] = '\0';
	if((len < 9) || (strncmp("name:",buff,5) != 0)){
		fprintf(stderr,"error:name is too short or null,name:%s\n",buff+5);
		goto error0;		
	}

	// 客户端没问题 开始连接服务器
	if((tcp_socket = socket(PF_INET,SOCK_STREAM,0)) == -1){
		perror("socket");
		goto error0;
	}
	memset(&peeraddr,0,sizeof(peeraddr));
	peer_len = sizeof(peeraddr);
	if(-1 ==  connect(tcp_socket,(struct sockaddr *)&myaddr,peer_len)){
		perror("server");
		goto error1;
	}
	// 发送name
	send(tcp_socket,buff,len,0);
	//len = recv(tcp_socket,buff,BUFF_SIZE,0);
	len = recv(tcp_socket,buff,BUFF_SIZE,0);
	buff[len] = '\0';
	if(strcase("success",buff) != 0){
		fprintf(stderr,"error:%s\n",buff);
		goto error1;
	}
	fputs("set name ok\n",stderr);
/***********************************/
	int max = tcp_socket>fw ? tcp_socket : fw;
	fd_set readfds,tmpfds;
//------
	FD_ZERO(&readfds);
	FD_SET(fw,&readfds);
	FD_SET(tcp_socket,&readfds);

//-----------------------------------------
	int n;
	int stat;
	while(1){
		tmpfds = readfds;
		n=select(max+1,&tmpfds,NULL,NULL,NULL);
	//	fprintf(stderr,"-------%d----------\n",n);
		if(FD_ISSET(tcp_socket,&tmpfds)){
			len = recv(tcp_socket,buff,BUFF_SIZE,0);
//			printf("recv len:%d\n",len);
			buff[len] = '\0';
			if(len <= 0){
				fprintf(stderr,"error:recv failed(len.%d)\n",len);
				goto error1;
			// }else if (len = 0){
			// 	switch(errno){
			// 		case 0:
			// 			break;
			// 		case 1:
			// 			break;
			// 	}
			// 	fprintf(stderr,"error:recv failed(len.%d)\n",len);
			// 	goto error;
			}else if(len > 0){
				if(strcmp("heartbeat\n",buff) == 0){
					// 心跳检查不用与脚本交互
					strsend(tcp_socket,"heartbeat\n");
					continue;
				}
				/**
				 * 输出到标准输出,并刷新缓冲, 将参数的导入到 运行脚本中
				 */
			/*	buff[len]='\0';
				puts(buff);
				fflush(stdout);
			*/
				if (len = write(1,buff,len) <= 0){
					fprintf(stderr,"error:write fialed(len.%d)\n",len);
					goto error1;
				}
			}
		}else if(FD_ISSET(fw,&tmpfds)){
			//memset(buff,0,BUFF_SIZE);
			if((len = read(fw,buff,BUFF_SIZE)) <= 0){
				fprintf(stderr,"error:read fialed(len.%d)\n",len);
				send(tcp_socket,"close\n",7,0);
				goto error1;
			}
			buff[len]='\0';
			if(strcmp("exit\n",buff) == 0){
				fputs("Bye.\n",stderr);
				goto error1;
			}
			fprintf(stderr,"recv_fifo:%s",buff);
			/*if(strcase("build_stat:",buff) == 0){
				if((len = send(tcp_socket,buff,len,0)) <= 0){
					fprintf(stderr,"error:send fialed(len.%d)\n",len);
					goto error1;
				}
			}else if (strcase("start-time",buff)){

			}
			continue;*/
			// 来自 本地管道,非 守护脚本
			if(strcase("manage:",buff) == 0){
				if (len = write(1,buff,len) <= 0){
					fprintf(stderr,"error:write fialed(len.%d)\n",len);
					goto error1;
				}
			}else if((len = send(tcp_socket,buff,len,0)) <= 0){
				fprintf(stderr,"error:send fialed(len.%d)\n",len);
				goto error1;
			}
		}else if (n < 0){
			perror("select");
			goto error1;
		}else {
			fprintf(stderr,"select:other(%d)(fw.%d,tcp.%d)\n",n,fw,tcp_socket);
		}
	}

	return 0;
error1:

	close(tcp_socket);
error0:
	close(fw);
	exit(1);
}
