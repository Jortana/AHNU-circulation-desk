# -*- coding: utf-8 -*-
import json, urllib.request, urllib.parse, urllib.error
from urllib.parse import urlencode
 
def main():
    appkey = "4144befbd9750192d324f1bdbc3a33b1"
    request_book(appkey,"GET")

 
#图书分类目录
def request1(appkey, m="GET"):
    url = "http://apis.juhe.cn/goodbook/catalog"
    params = {
        "key" : appkey, #应用APPKEY(应用详细页查询)
        "dtype" : "", #返回数据的格式,xml或json，默认json
 
    }
    params = urlencode(params)
    if m =="GET":
        f = urllib.request.urlopen("%s?%s" % (url, params))
    else:
        f = urllib.request.urlopen(url, params)
 
    content = f.read()
    res = json.loads(content)
    if res:
        error_code = res["error_code"]
        if error_code == 0:
            #成功请求
            print(res["result"])
        else:
            print("%s:%s" % (res["error_code"],res["reason"]))
    else:
        print("request api error")
 
#图书内容
def request_book(appkey, m="GET"):
    url = "http://apis.juhe.cn/goodbook/query"
    pn = 1
    params = {
        "key" : appkey, #应用APPKEY(应用详细页查询)
        "catalog_id" : "1", #目录编号
        "pn" : "", #数据返回起始
        "rn" : "1", #数据返回条数，最大30
        "dtype" : "", #返回数据的格式,xml或json，默认json
    }
    params = urlencode(params)
    if m =="GET":
        f = urllib.request.urlopen("%s?%s" % (url, params))
    else:
        f = urllib.request.urlopen(url, params)
 
    content = f.read()
    res = json.loads(content.decode('utf-8'))
    if res:
        error_code = res["error_code"]
        if error_code == 0:
            #成功请求
            write_data(res['result'])
        else:
            print("%s:%s" % (res["error_code"],res["reason"]))
    else:
        print("request api error")

def write_data():
    # 解析数据，写入文件
    print("")

if __name__ == '__main__':
    main()