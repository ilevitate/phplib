<?php
/**
 * Created by PhpStorm.
 * User: iLevitate
 * Date: 2018/10/11
 * Time: 11:11
 */

namespace Utils;


use DateTime;

class TimeUtil
{

    /**
     * 判断时间是否是7天前
     * @param $time
     * @return bool
     */
    public static function isSevenDaysAgo($time)
    {
        $sign = time() - (60 * 60 * 24 * 7);
        if ($time < $sign) {
            return true;
        }

        return false;
    }


    /**
     * 格式化当前时间
     * @param string $format
     * @return false|string
     */
    public static function formatTimeNowToYmdHis($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }

    /**
     * 格式化时间戳
     * @param string $format
     * @param $time
     * @return false|string
     */
    public static function formatTimeToYmdHis($time, $format = 'Y-m-d H:i:s')
    {
        return date($format, $time);
    }


    /**
     * 格式化时间戳
     * @param string $format
     * @param $time
     * @return false|string
     */
    public static function formatTimeToYmd($time, $format = 'Ymd')
    {
        return date($format, $time);
    }


    /**
     * 格式化时间戳
     * @param string $format
     * @param $time
     * @return false|string
     */
    public static function formatTimeToY_m_d($time, $format = 'Y-m-d')
    {
        return date($format, $time);
    }


    /**
     * 计算倒计时
     * @param $endTime
     * @param $nowTime
     * @return int|string
     */
    public static function TimeToEnd($endTime, $nowTime)
    {
        $remain_time = $endTime - $nowTime; //剩余的秒数
        $remain_hour = floor($remain_time / (60 * 60)); //剩余的小时
        $remain_minute = floor(($remain_time - $remain_hour * 60 * 60) / 60); //剩余的分钟数
        $remain_second = ($remain_time - $remain_hour * 60 * 60 - $remain_minute * 60); //剩余的秒数
        if ($remain_time <= 0) {
            $da = 0;
        } else {
            $da = $remain_hour . '时' . $remain_minute . '分' . $remain_second . '秒';
        }

        return $da;
    }

    /**
     * 获取今天0点时间戳
     * @return false|int
     */
    public static function getTodayStartTime()
    {
        $dateStr = date('Y-m-d', time());

        return $startTime = strtotime($dateStr);

    }

    /**
     * 获取今天23:59:59点时间戳
     * @return false|int
     */
    public static function getTodayEndTime()
    {
        $dateStr = date('Y-m-d', time());
        $endTime = strtotime($dateStr) + 86400;

        return $endTime;
    }

    /**
     * 获取昨天0点时间戳
     * @return false|int
     */
    public static function getYesterdayBeginTime()
    {
        return $beginYesterday = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
    }

    /**
     * 获取昨天23:59:59点时间戳
     * @return false|int
     */
    public static function getYesterdayEndTime()
    {
        return $endYesterday = mktime(0, 0, 0, date('m'), date('d'), date('Y')) - 1;
    }

    /**
     * 获取指定日期的开始和结束时间戳
     * @param $date
     * @return array
     */
    public static function getDateBeginAndEndTime($date)
    {
        return [
            'begin' => strtotime($date),
            'end' => strtotime($date) + 60 * 60 * 24,
        ];
    }


    /**
     * 返回指定时间戳是当前时间的多少时间之前
     * @param $time
     * @return string
     */
    public static function desc($time)
    {
        $nowTime = time();
        $second = $nowTime - $time;
        if ($second < 60) {
            $desc = $second . '秒前';
        } elseif ($second < 60 * 60) {
            $desc = floor($second / 60) . '分钟前';
        } elseif ($second < 60 * 60 * 24) {
            $desc = floor($second / (60 * 60)) . '小时前';
        } else {
            $desc = floor($second / (60 * 60 * 24)) . '天前';
        }

        return $desc;
    }

    /**
     * 求两个日期之间相差的日期
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public static function getDatesBetweenTwoDays($startDate, $endDate)
    {
        $dates = [];
        array_push($dates, $startDate);
        $currentDate = $startDate;
        do {
            $nextDate = date('Ymd', strtotime($currentDate . ' +1 days'));
            array_push($dates, $nextDate);
            $currentDate = $nextDate;
        } while ($endDate != $currentDate);

        return $dates;

    }


    /**
     * 求两个日期之间相差的天数
     * (针对1970年1月1日之后，求之前可以采用泰勒公式)
     * @param string $day1
     * @param string $day2
     * @return number
     */
    public static function diffBetweenTwoDays($day1, $day2)
    {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);

        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }

        return ($second1 - $second2) / 86400;
    }


    /***
     * 计算两个时间的间隔天数
     * @param datetime $date1 减数
     * @param datetime $date2 被减数
     * @return string {$d}天{$h}小时{$m}分
     */
    public static function getDateDiff($date1, $date2)
    {
        $a = strtotime($date1);
        $b = strtotime($date2);
        $cle = $a - $b;

        $d = floor($cle / 3600 / 24);
        $h = floor(($cle % (3600 * 24)) / 3600);  //%取余
        $m = floor(($cle % (3600 * 24)) % 3600 / 60);
        return "{$d}天{$h}小时{$m}分";
    }

    /**
     *  秒转换为 小时分钟
     * @param $times 秒
     * @param string $format 格式化
     * @return  string
     */
    public static function secondToTime($times, $format = '%s天%s小时%s分钟')
    {
        $result = '00:00:00';
        if ($times > 0) {
            $hour = floor($times / 3600);
            $minute = floor(($times - 3600 * $hour) / 60);
            $second = floor((($times - 3600 * $hour) - 60 * $minute) % 60);
            $result = sprintf($format, $hour, $minute, $second);
        }
        return $result;
    }

}