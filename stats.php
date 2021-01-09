<?php
//اختلاف زمانی سرور
$time_zone = 12600;

//تاریخ امروز
$today = date("Y-m-d", time() + $time_zone);

//تاریخ دیروز
$yesterday = date("Y-m-d", time() - 86400 + $time_zone);

//متغیرهای پیش فرض
$week_visit = 0;
$month_visit = 0;
$month_str = null;

//آدرس فایل
$file_src = 'include/visit-stats.txt';
chmod($file_src, 0755);

//خواندن فایل
$read_file = file_get_contents($file_src);

//اگر فایل خالی نبود
if (filesize($file_src) > 0 || $read_file != ''){
    $split_file = explode('|', $read_file);
    
    //print_r($split_file);
    $modify = $split_file[31];
    
    //اگر تاریخ آخرین ویرایش برابر تاریخ امروز نبود
    if($modify != $today){
        $today_visit = 1;
        
        if($modify == $yesterday){
            $yesterday_visit = $split_file[0];
        } else{
            $yesterday_visit = 0;
        }
        
        $week_visit += $today_visit + $yesterday_visit;
        $month_visit += $today_visit + $yesterday_visit;
        
        for($i = 2; $i < 30; $i++) {
            $month_str .= $split_file[$i - 1];
            
            if($i < 29) {
                $month_str .= '|';
            }
            
            if($i < 8) {
                $week_visit += $split_file[$i - 1];
            }
            
            $month_visit += $split_file[$i - 1];
        }
        
        $total_visit = $split_file[30] + 1;
        $last_modify = $today;
    } //اگر تاریخ آخرین ویرایش برابر امروز بود
    else{
        $today_visit = $split_file[0] + 1;
        $yesterday_visit = $split_file[1];
        $week_visit += $today_visit + $yesterday_visit;
        $month_visit += $today_visit + $yesterday_visit;
        
        for($i = 2; $i < 30; $i++) {
            $month_str .= $split_file[$i];
            
            if($i < 29) {
                $month_str .= '|';
            }
            
            if($i < 8) {
                $week_visit += $split_file[$i];
            }
            
            $month_visit += $split_file[$i];
        }
                            
        $total_visit = $split_file[30] + 1;
        $last_modify = $today;
    }
} //اگر فایل خالی بود
else{
    $today_visit = 1;
    $yesterday_visit = 0;
    $week_visit = 1;
    $month_visit = 1;
    
    for($i = 2; $i < 30; $i++) {
        $month_str .= '0';
        
        if($i < 29) {
            $month_str .= '|';
        }
    }
    
    $total_visit = 1;
    $last_modify = $today;
}

//نوشتن آمار جدید در فایل
$file_src_handle = fopen($file_src, 'w+');
$visit_data = $today_visit . '|' . $yesterday_visit . '|' . $month_str . '|' . $total_visit . '|' . $last_modify;
fwrite($file_src_handle, $visit_data);
fclose($file_src_handle);

//محاسبه تعداد کاربران آنلاین
$config_array = array(
'user_time' => date("YmdHis", time() + $time_zone),
'user_ip' => $_SERVER['REMOTE_ADDR'],
'file_name' => 'include/visit-online.txt'
);
chmod($config_array['file_name'], 0755);

//خواندن اطلاعات فایل
$online_file = file_get_contents($config_array['file_name']);

//تجزیه به آرایه
$online_file = explode("\r\n", $online_file);

//حذف مقادیر خالی
foreach($online_file as $key=> $value){
    if(is_null($value) || $value == ''){
        unset($online_file[$key]);
    }
}

//حذف آی پی های قدیمی و آی پی فعلی
foreach($online_file as $key=> $value){
    $user_ip_time = explode("|", $value);
    if($user_ip_time[1] <= date("YmdHis", time() + $time_zone - 300)){
        unset($online_file[$key]);
    }
    
    if($user_ip_time[0] == $config_array['user_ip']){
        unset($online_file[$key]);
    }
}

//محاسبه تعداد افراد آنلاین
$online = 1;
foreach($online_file as $online_users){
    $user_ip_time = explode("|", $online_users);
    if($user_ip_time[1] >= date("YmdHis", time() + $time_zone - 300)){
        $online++;
    }
}

//آمار کاربرانی که آنلاین هستند به اضافه کاربر فعلی
$new_online = $config_array['user_ip'] . "|" . $config_array['user_time'] . "\r\n";;
foreach($online_file as $key=> $value){
    $new_online .= $value . "\r\n";
}

//نوشتن آمار جدید در فایل
$file_src_handle = fopen($config_array['file_name'], 'w+');
fwrite($file_src_handle, $new_online);
fclose($file_src_handle);

//////////////// webgaran.ir ///////////////

//گرفتن خروجی
echo '<div class="stats">
» بازدید امروز: ' . $today_visit . '<br>
» بازدید دیروز: ' . $yesterday_visit . '<br>
» بازدید هفته: ' . $week_visit . '<br>
» بازدید ماه: ' . $month_visit . '<br>
» بازدید کل: ' . $total_visit . '<br>
» افراد آنلاین: ' . $online . '
</div>';
?>