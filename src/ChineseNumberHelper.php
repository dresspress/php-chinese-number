<?php

namespace DressPress\ChineseNumber;

class ChineseNumberHelper {
    /**
     * Undocumented function
     * 
     * @link https://learnku.com/articles/39405
     *
     * @param string|int $num
     * @return string
     */
    public static function toChinese($num) {
        $chiNum = array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九');
        $chiUni = array('', '十', '百', '千', '万', '十', '百', '千', '亿');
        $chiStr = '';
        $num_str = (string)$num;
        $count = strlen($num_str);
        $last_flag = true; //上一个 是否为0
        $zero_flag = true; //是否第一个
        $temp_num = null; //临时数字
        $chiStr = ''; //拼接结果
        if ($count == 2) { //两位数
            $temp_num = $num_str[0];
            $chiStr = $temp_num == 1 ? $chiUni[1] : $chiNum[$temp_num] . $chiUni[1];
            $temp_num = $num_str[1];
            $chiStr .= $temp_num == 0 ? '' : $chiNum[$temp_num];
        } else if ($count > 2) {
            $index = 0;
            for ($i = $count - 1; $i >= 0; $i--) {
                $temp_num = $num_str[$i];
                if ($temp_num == 0) {
                    if (!$zero_flag && !$last_flag) {
                        $chiStr = $chiNum[$temp_num] . $chiStr;
                        $last_flag = true;
                    }

                    if ($index == 4 && $temp_num == 0) {
                        $chiStr = "万" . $chiStr;
                    }
                } else {
                    if ($i == 0 && $temp_num == 1 && $index == 1 && $index == 5) {
                        $chiStr = $chiUni[$index % 9] . $chiStr;
                    } else {
                        $chiStr = $chiNum[$temp_num] . $chiUni[$index % 9] . $chiStr;
                    }
                    $zero_flag = false;
                    $last_flag = false;
                }
                $index++;
            }
        } else {
            $chiStr = $chiNum[$num_str[0]];
        }
        return $chiStr;
    }

    public static function toNumber($str) {
        return self::tonumber2($str);
    }

    /**
     * Undocumented function
     * 
     * @link https://learnku.com/articles/39405
     *
     * @param string $string
     * @return string
     */
    private static function toNumber1($string) {

        if (is_numeric($string)) {
            return $string;
        }
        // '仟' => '千','佰' => '百','拾' => '十',
        $string = str_replace('仟', '千', $string);
        $string = str_replace('佰', '百', $string);
        $string = str_replace('拾', '十', $string);
        $num = 0;
        $wan = explode('万', $string);
        if (count($wan) > 1) {
            $num += self::toNumber1($wan[0]) * 10000;
            $string = $wan[1];
        }
        $qian = explode('千', $string);
        if (count($qian) > 1) {
            $num += self::toNumber1($qian[0]) * 1000;
            $string = $qian[1];
        }
        $bai = explode('百', $string);
        if (count($bai) > 1) {
            $num += self::toNumber1($bai[0]) * 100;
            $string = $bai[1];
        }
        $shi = explode('十', $string);
        if (count($shi) > 1) {
            $num += self::toNumber1($shi[0] ? $shi[0] : '一') * 10;
            $string = $shi[1] ? $shi[1] : '零';
        }
        $ling = explode('零', $string);
        if (count($ling) > 1) {
            $string = $ling[1];
        }
        $d = array(
            '一' => '1', '二' => '2', '三' => '3', '四' => '4', '五' => '5', '六' => '6', '七' => '7', '八' => '8', '九' => '9',
            '壹' => '1', '贰' => '2', '叁' => '3', '肆' => '4', '伍' => '5', '陆' => '6', '柒' => '7', '捌' => '8', '玖' => '9',
            '零' => 0, '0' => 0, 'O' => 0, 'o' => 0,
            '两' => 2
        );
        return $num + @$d[$string];
    }

    /**
     * Undocumented function
     * @link https://gist.github.com/sweetOranges/6b1eeae9a88ef3b67d1d2ebd74728c42
     *
     * @param [type] $str
     * @return string
     */
    private static function toNumber2($str) {
        //汉字装换数字的对照表
        $number_map = array('零' => 0, '一' => 1, '二' => 2, '三' => 3, '四' => 4, '五' => 5, '六' => 6, '七' => 7, '八' => 8, '九' => 9);
        $step_map = array('十' => 10, '百' => 100, '千' => 1000);
        $bigStep_map = array('万' => '10000', '亿' => 100000000);
        //操作数栈，值栈
        $opStack = array(1);
        $valStack = array();
        //以万,亿为分割单位对数字进行拆分计算
        //例如:三千五百万一千一百零五，对其进
        //行分别入op,val栈
        //				op 中为1,10000
        //				val中为1105,3500
        //最后将栈进行对应合并操作,得出操作数
        for ($i = mb_strlen($str) - 1; $i >= 0; $i--) {
            $_val  = 0;
            $_step = 1;
            for ($j = $i; $j >= 0; $j--) {
                $_char = mb_substr($str, $j, 1);
                if (array_key_exists($_char, $number_map)) {
                    $_val += $_step * $number_map[$_char];
                }
                if (array_key_exists($_char, $step_map)) {
                    $_step = $step_map[$_char];
                }
                $i = $j;
                if (array_key_exists($_char, $bigStep_map)) {
                    array_push($opStack, $bigStep_map[$_char]);
                    break;
                }
            }
            array_push($valStack, $_val);
        }
        $number = 0;
        //合并操作数
        while (count($opStack) > 0) {
            $va = array_pop($valStack);
            $op = array_pop($opStack);
            $number += $va * $op;
        }
        //检查两栈是否都弹出完毕，否则表达式错误
        if (count($opStack) == 0 && count($valStack) == 0) {
            return $number;
        } else {
            return False;
        }
    }
}
