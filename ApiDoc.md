# API文档v1.0.0

## 常规API调用原则

- 所有的API都以domain.com/api/开头
  - API分为两部分
    - domain.com/api/part_1/part_2
        - part_1为model名，如user或question
        - part_2为action名，如reset_password
    
- CRUD 
    - 每个model都有增删查改的方法，分别对应add,remove,change,read

## MODEL
### Question
#### 字段注释
- `id` : 问题ID
- `title` : 标题
- `desc` : 描述
####   `add`
- 权限:已登录
- 传参:
    - 必填:`title`
    - 可选:`desc`
    
#### `change`
- 权限:已登录且为问题提问者
- 传参
    - 必填:`id`
    - 可选:`title`,`desc`
    

```
API设计需要注意的问题

1.命名清晰
2.对于新的功能以及新的思想，最好用形象的名词来说明
3.第三方的API一般要有版本号

```
