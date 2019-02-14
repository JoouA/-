<?php
// 给一个时间，获取这个月的结束的日期。比如 输入'2018-08-04' 输出'2018-08-31'
function monthDay($date) {
     $month31 = [1, 3, 5, 7, 8, 10, 12];
    list($year, $month) = explode('-',$date);
    if ($month != 2) {
        if (in_array($month, $month31)) {
            return "{$year}-{$month}-31";
        } else {
            return "{$year}-{$month}-30";
        }
    }
      if (  $year%4==0  && ($year%100!=0 ||  $year%400==0 ) ){
        return "{$year}-{$month}-29";
    }else{
        return "{$year}-{$month}-28";
    }
}

// echo monthDay('2018-08-04');


/*
方法二
方法一的代码看着没啥问题，但是可能是一种特别复杂的实现方式，它考虑的因素比较多。
我们可以换种思考的方式，这个月有多少天，我们知道一个月的开始时间和一个月的结束时间 两者相减，也是等于当月的天数。一月末正好是一个月的开始。而且每个月的开始时间是固定的，都是1号，不会变的。下一个月的月数等于当月+1。
但是有个特殊的情况，如果是年底，那么12月的下一月就是新的一年的1月。
*/

function endDayOfMonth($date)
{
	list($year, $month) = explode('-', $date);

	$nextYear = $year;
	$nextMonth = $month + 1;

	// 如果底月是12月，下个月就是1月
	if ($month == 12) {
		$nextMonth = "01";
		$nextYear = $year + 1;
	}

	$begin = "{$year}-{$month}-01 00:00:00";
	$end = "{$nextYear}-{$nextMonth}-01 00:00:00";
	$day = (strtotime($end) - strtotime($begin)) / (24 * 60 * 60);

	return "{$year}-{$month}-{$day}"; 
}




// 方法二的方法其实已经差不多接近了，但是还是可能不够特别好的。因为我们不需要算天数。我们知道新的一个月的第一天，减去一个1，就是当月的最后一秒。

function endDayOfMonthMethod2($date) {
    list($year, $month) = explode('-',$date);
    $nextYear = $year;
    $nexMonth = $month+1;
    //如果是年底12月 下个月就是1月
    if($month == 12) {
        $nexMonth = "01";
        $nextYear = $year+1;
    }
    $end = "{$nextYear}-{$nexMonth}-01 00:00:00";
    $endTimeStamp = strtotime($end) - 1 ;
    return date('Y-m-d',$endTimeStamp);
}


echo endDayOfMonthMethod2('2018-2-04');

echo "\n";

$date = '2018-08-08';
echo date('Y-m-t',strtotime($date));

?>