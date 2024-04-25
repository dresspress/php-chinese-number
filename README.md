# PHP Chinese Number

简单的阿拉伯数字和中文数字的相互转换。

## 有多简单?

目前**只支持最基本的阿拉伯数字格式(324)和中文数字格式(三百二十四)**互转，**不考虑逗号、货币、小数点、百分比**等复杂需求。如果需要这些复杂的功能，可Github搜索PHP Chinese Number, 已有相关的库实现比较完善的**阿拉伯数字转中文数字表达**的各种需求。

## 为什么创建这个包?

Github上的同类项目，只支持阿拉伯转中文，不支持中文转阿拉伯。为了方便自己Composer而制作此包, 代码复制修改自网络。

## 阿拉伯数字=>中文数字

```
ChineseNumberHelper::toChinese($number)
```

## 中文数字=>阿拉伯数字

```
ChineseNumberHelper::toNumber($chinese)
```
