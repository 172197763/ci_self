# ci改造框架


由原生框架修改而成，变得更方便使用.

## 更新点如下:

1. 公共函数新增p()函数:格式化打印函数
2. 公共函数新增json()函数:直接转json字符串后输出
3. system/core/Log新增ERROR级日志提醒(目前推送到钉钉机器人)，需要配置该文件259行accesstoken才能启用
4. system/core/Output 509新增DEUBG级日志打印访问的控制器&方法&耗时
5. 若干个常用helper整理

😀谢谢大家！