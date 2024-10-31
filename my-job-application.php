<?php
/*
Plugin Name: My Job Application
Plugin URI: http://wordpress.org/extend/plugins/my-job-application/
Description: My Job Application is a Wordpress plugin developed using PHP and MySQL. It provides a simple way of using the Indeed.com API feed in your Wordpress blog. It also allows you to create a job database using your own job sources and create a custom job listing for your blog.  This version uses cURL to retrieve the file from Indeed.com instead of SimpleXML.  SimpleXML is still used to parse the XML file.
Version: 1.40
Author: customnetware
Author URI: http://customnetware.com
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/
error_reporting(E_ERROR | E_WARNING | E_PARSE);
function my_private_job_listing(){
	//gets job listing from the joblisting table on the current database
	global $wpdb;
	$cnw_job_sql="SELECT * FROM wp_cnw_joblist ORDER BY job_id";
	$cnw_job_results= $wpdb->get_results($wpdb->prepare($cnw_job_sql));
	for($i = 0; $i<count($cnw_job_results); $i++)
	{
		$j_url=$cnw_job_results[$i]->job_url;
		$j_email=$cnw_job_results[$i]->job_poster;
		$j_id=$cnw_job_results[$i]->job_id;
		$cnw_joblist.="<div style=\"font-size: 10pt; margin-left: 10px; \">";
		$cnw_joblist.="<a href=$j_url>".$cnw_job_results[$i]->job_title."</a><br />";
		$cnw_joblist.="<div style=\"margin-bottom:5px\"><b>*Posted by ".get_bloginfo('name')." on ".$cnw_job_results[$i]->job_post_date."";
		$cnw_joblist.=" (ID=".$j_id.")"."</b></div>";
		$cnw_joblist.=$cnw_job_results[$i]->job_desc."<br />";
		$cnw_joblist.="<div style=\"margin-top:5px;margin-bottom:5px\"><b>Job is located at ".$cnw_job_results[$i]->job_company.", ";
		$cnw_joblist.=$cnw_job_results[$i]->job_loc."</b></div></div>";
	}
	return $cnw_joblist;
}
function my_job_list_form() {
global $jobs,$cnw_user;
if (isset($_POST["nav_status"])){
if ($_POST["nav_status"]=='m'){
$jobs->setage='';
$jobs->setradius='checked=checked';
}
if ($_POST["nav_status"]=='a'){
$jobs->setage='checked=checked';
$jobs->setradius='';
}
}else{
$jobs->setradius='checked=checked';
$jobs->setage='';	
}



?>
<style type="text/css">
input.longsubmit {font-size: 12px; border:1px solid #0099FF; padding:1px 5px 1px 4px; width:150px}
input.shortsubmit {font-size: 12px; border:1px solid #0099FF;padding:1px 4px 1px 4px;width:50px}
hr.jobBar {color:#0099FF;background-color:#0099FF;height:3px}
</style>

	<script type="text/javascript" src="http://www.indeed.com/ads/apiresults.js"></script>
	<div style='font-size: 10pt; margin-left:10px; width:550px'>
	<hr class="jobBar">
	<form action="<?php echo get_option('cnw_job_page_url') ?>" method='post'>
	<table><tr>
	<td>Zip/City,St<br><input type='text' name='city' value='<?php echo$jobs->city ?>' size='25' style='text-align:left'></td>
	<td>Search Criteria<br><input type='text' name='qry' value='<?php echo $jobs->qry ?>'  size='50' ></td>
	</tr>
	<tr align=left><td colspan=2 nowrap="nowrap">
	<em>When clicking Age/Radius buttons, increase/decrease: 
	<input type='radio' name='nav_status' value='m' <?php echo $jobs->setradius ?>> Search radius 
	<input type="radio" name='nav_status' value='a' <?php echo $jobs->setage ?>> Job age</em><br>
	<em>Displaying <?php echo $jobs->start ?>-<?php echo $jobs->end ?> of <?php echo $jobs->total ?> jobs posted in the last <?php echo $jobs->jobage ?> days within a <?php echo $jobs->radius ?> mile radius.</em>
	</td></tr>
	<tr align=center><td colspan=2 nowrap="nowrap"><br>
	<div style="margin-bottom:10px">
	<input type='submit' name='back' value='Back' class="shortsubmit" <?php if($jobs->total<11)echo 'disabled' ?>>
	<input type='submit' name='less' value='Decrease Age/Radius' class="longsubmit" >
	<input type='submit' name='search' value='Search' class="shortsubmit">
	<input type='submit' name='save' value='Save' class="shortsubmit" <?php if (0 == $cnw_user->ID) echo 'disabled'?>>
	<input type='submit' name='more' value='Increase Age/Radius' class="longsubmit">
	<input type='submit' name='next' value='Next' class="shortsubmit" <?php if($jobs->total<11)echo 'disabled' ?>>
	</div>	
	</td></tr>
	</table>
	<input type="hidden" name='jobage' value='<?php echo $jobs->jobage ?>'>
	<input type="hidden" name='radius' value='<?php echo $jobs->radius ?>'>
	<input type="hidden" name='start' value='<?php echo $jobs->start ?>'>
	<input type="hidden" name='end' value='<?php echo $jobs->end ?>'>
	<input type="hidden" name='total' value='<?php echo $jobs->total ?>'>
	</form><hr class="jobBar">
	
	</div>

<?php

 }
function my_job_list_footer(){
	//displays the job page footer
	$list_footer.='<div id=indeed_at><a href="http://www.indeed.com/">jobs</a>';
	$list_footer.=' by <a href="http://www.indeed.com/" title="Job Search">';
	$list_footer.='<img src="http://www.indeed.com/p/jobsearch.gif" style="border: 0;';
	$list_footer.='vertical-align: middle;" alt="Indeed job search"></a></div>';
	return $list_footer;
}
function my_xml_query(){
global $jobs,$cnw_user;
$jobs = new stdClass();
	
if (isset($_POST["qry"])) $jobs->qry=mysql_real_escape_string(urlencode($_POST["qry"])); else $jobs->qry="management";
if (isset($_GET["qry"])) $jobs->qry=mysql_real_escape_string(urlencode($_GET["qry"]));
if (isset($_POST["city"])) $jobs->city=mysql_real_escape_string(urlencode($_POST["city"])); else $jobs->city='Stockton,CA';
if (isset($_GET["city"])) $jobs->city=mysql_real_escape_string(urlencode($_GET["city"]));
if (isset($_POST["jobage"])) $jobs->jobage=mysql_real_escape_string($_POST["jobage"]); else $jobs->jobage=7;
if (isset($_POST["radius"])) $jobs->radius=mysql_real_escape_string($_POST["radius"]); else $jobs->radius=30;
$jobs->site='employer';
$jobs->jobtype='fulltime';
$jobs->limit=10;

$cnw_user=wp_get_current_user();
if (0 !== $cnw_user->ID){
if (isset($_POST["save"])){
update_user_meta($cnw_user->ID,'mja_job_qry',$jobs->qry);
update_user_meta($cnw_user->ID,'mja_job_age',$jobs->jobage);
update_user_meta($cnw_user->ID,'mja_job_loc',$jobs->city);
update_user_meta($cnw_user->ID,'mja_job_miles',$jobs->radius);
update_user_meta($cnw_user->ID,'mja_job_type',$jobs->jobtype);
}
		
if (!$_POST){
$jobs->qry=get_user_meta($cnw_user->ID,'mja_job_qry',true);
$jobs->jobage=trim(get_user_meta($cnw_user->ID,'mja_job_age',true));
$jobs->city=get_user_meta($cnw_user->ID,'mja_job_loc',true);
$jobs->radius=trim(get_user_meta($cnw_user->ID,'mja_job_miles',true));
$jobs->jobtype=get_user_meta($cnw_user->ID,'mja_job_type',true);	
}		
}
	if (isset($_POST["more"])){
		if ($_POST["nav_status"]=="a") $jobs->jobage=$jobs->jobage+7;
		if ($_POST["nav_status"]=="m") $jobs->radius=$jobs->radius+10;	
	}
	
	if (isset($_POST["less"])){
		if ($_POST["nav_status"]=="a"&&$jobs->jobage>0) $jobs->jobage=$jobs->jobage-7;
		if ($_POST["nav_status"]=="m"&&$jobs->radius>0) $jobs->radius=$jobs->radius-10;
	}
	if (isset($_POST["next"])){
	$jobs->start=$_POST["end"];
	if ($jobs->start==$_POST["total"]) $jobs->start=0;
	}
if (isset($_POST["back"])) $jobs->start=0;

}
function my_xml_string(){
global $jobs;
$site_id = get_option('cnw_job_search_api_key');
$file_str="http://api.indeed.com/ads/apisearch?publisher=".$site_id."&q=".$jobs->qry;
$file_str.="&l=".$jobs->city."&sort=&radius=".$jobs->radius."&st=".$jobs->site."&jt=".$jobs->jobtype;
$file_str.="&start=".$jobs->start."&limit=".$jobs->limit."&fromage=".$jobs->jobage;
$file_str.="&filter=&latlong=1&co=us&chnl=&userip=".$_SERVER["REMOTE_ADDR"];
$file_str.="&useragent=".$_SERVER["HTTP_USER_AGENT"]."&v=2";

$ch=curl_init(); 
       curl_setopt($ch, CURLOPT_URL, $file_str); 
       curl_setopt($ch, CURLOPT_TIMEOUT, 180); 
       curl_setopt($ch, CURLOPT_HEADER, 0); 
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
       curl_setopt($ch, CURLOPT_GET, 1); 
       $data=curl_exec($ch);
       curl_close($ch); 
return $data;

}
function my_job_list_display($list_string){
global $jobs;
//reads the xml returned from Indeed.com, formats and displays the results on the selected page or post
	$xml = simplexml_load_string(stripslashes($list_string));


	$jobs->qry=trim($xml->query);
	$jobs->city=trim($xml->location);
	$jobs->total=trim($xml->totalresults);
	$jobs->start=trim($xml->start);
	$jobs->end=trim($xml->end);


	$job_title=$xml->xpath("//jobtitle");
	$job_desc=$xml->xpath("//snippet");
	$job_key=$xml->xpath("//jobkey");
	$job_city=$xml->xpath("//city");
	$job_state=$xml->xpath("//state");
	$job_emp=$xml->xpath("//company");
	$job_date=$xml->xpath("//date");

	$body_display="<div style=\"font-size: 10pt; margin-left: 10px; \">";
	$body_display.= $list_status;
	$job_page_url=get_option('cnw_job_page_url');
	$pos = strrpos($job_page_url, "?");
	if ($pos === false){
		$job_page_url.="?";
		}
		else{
		$job_page_url.="&";	
		}
	$job_page_url_city=$job_page_url."city=";
	$job_page_url_criteria=$job_page_url."qry=";
	
	for ($i=0;$i<count($job_title); $i++)
	{
		$body_display.="<b><a target=_blank href='http://www.indeed.com/rc/clk?jk=$job_key[$i]&indpubnum=".$site_id."&from=vj'>";
		$body_display.=$job_title[$i]."</a></b><br />";
		$body_display.="<div style=\"margin-bottom:5px\">Posted by ";
		$body_display.="<a href='".$job_page_url_criteria.$job_emp[$i]."'>".$job_emp[$i]."</a>";
		$body_display.=" on ".$job_date[$i]."</div>";
		$body_display.=$job_desc[$i]."<div style=\"margin-top:5px\"> Job is located in ";
		$body_display.="<a href='".$job_page_url_city.$job_city[$i].",".$job_state[$i]."'>".$job_city[$i].",".$job_state[$i]."</a>";
		$body_display.="</div><br />";
	}
	$body_display.="</div>";
	
	return $body_display;
}
function my_job_listing(){
global $jobs;
$xml_data=my_xml_query();
$xml_string=my_xml_string();
$show_job_list=my_job_list_display($xml_string);

if (isset($_POST["nav_status"])){
if ($_POST["nav_status"]=='a') $set_radius="checked";$set_age="";
if ($_POST["nav_status"]=='m') $set_radius="";$set_age="checked";	
}else{
$set_radius="";$set_age="checked";		
}

$show_form=my_job_list_form();
$show_job_list_footer=my_job_list_footer();
return $show_job_list.$show_job_list_footer;
}
function my_job_list($job_content){//inserts the job listing on the pages selected
	if (preg_match('{my_job_listing}',$job_content))
	{
		$job_output = my_job_listing();
		$job_content = str_replace('{my_job_listing}',$job_output,$job_content);
	}
	return $job_content;
}
add_filter("the_content","my_job_list");

function my_job_search_admin() {
	include_once('my-job-application-admin.php');
}
function my_job_search_admin_actions() {

	if(current_user_can('level_10'))
		add_options_page("My Job Application", "My Job Application", 1, "MyJobApplication", "my_job_search_admin");
}
add_action('admin_menu', 'my_job_search_admin_actions');

function my_job_table() {
	//add table to hold custom job listings on activation of plugin
	global $wpdb;
	
	$table_name = $wpdb->prefix . "cnw_joblist";
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE " . $table_name . " (
		job_id mediumint(9) NOT NULL AUTO_INCREMENT,
		job_title tinytext NOT NULL,
		job_desc text NOT NULL,
		job_company tinytext NOT NULL,
		job_loc tinytext NOT NULL,
		job_url tinytext NOT NULL,
		job_poster tinytext NOT NULL,
		job_post_date tinytext NOT NULL,
		UNIQUE KEY id (job_id)
			);";
		
		require_once(ABSPATH.'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		
	}
}
register_activation_hook( __FILE__, 'my_job_table' );
?>