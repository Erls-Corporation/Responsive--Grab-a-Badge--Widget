<?
global $wpdb;
if (!$wpdb) require_once($_SERVER["DOCUMENT_ROOT"]."/wp-content/themes/insider/_includes/db/quick_db.php");

global $siteid;
$siteid=0;
define("MK_UPLOAD_URL",lggrababadge_getDomain()."/wp-uploads/");

function lggrababadge_getDomain($forcenotsecure=false){
	$curPageURL = "";
	if ($_SERVER["HTTPS"] != "on" || $forcenotsecure) {
		$curPageURL .= "http://";
	} else {
		$curPageURL .= "https://";
	}
	
	
		$curPageURL .= $_SERVER["SERVER_NAME"];
	
	return $curPageURL;
}

function db_get_advocacy_site_id($domain)
{
	
global $wpdb;
global $siteid;
if ($siteid==0)
	{
		$src=(isset($_GET['src']))? $_GET['src'] : 'xyz'; 	
		$wpdb->show_errors();
	$sql = $wpdb->prepare("select advocacy_site_id from {$wpdb->prefix}advocacy_sites a		
				where a.domain='%s' or sequence_no='%s' limit 1",array($domain,$src));
	$s=$wpdb->get_row($sql);
	$siteid= ((int)$s->advocacy_site_id<=0)?1:(int)$s->advocacy_site_id;			 	
	}
return 	$siteid;
}

function lggrababadge_db_get_badgesBySite2($domain)
{
	global $wpdb;
	$uploadpath = MK_UPLOAD_URL."badges/badge";
	$badgepath=  lggrababadge_getDomain(true) ."/grab-a-badge/";
	$s=db_get_advocacy_site_id($domain);
	$wpdb->show_errors();



	$sql = "select b.badge_id,b.name,asbg.sort_order, bg.title, bg.description as group_description,
			b.description_1,b.question,b.description_2,answer_1,answer_2,b.download_count,verb,
			concat('{$uploadpath}',b.badge_id,'.png') as image,
			concat('{$uploadpath}',b.badge_id,'.zip') as zip,
			
			
				if(b.url_slug='',
					concat('{$badgepath}',urlencode(trim(replace(lower(b.name), '<sup>&reg;</sup>',''))),'/'),
					concat('{$badgepath}',urlencode(trim(replace(lower(b.url_slug), '<sup>&reg;</sup>',''))),'/')
				)
			 AS path,email

			from
			{$wpdb->prefix}badges b, 
			{$wpdb->prefix}badges_badge_groups bbg,
			{$wpdb->prefix}badge_groups bg,
			{$wpdb->prefix}advocacy_site_badge_groups asbg
			
			where 
			b.is_active=1 and
			bg.is_active=1 and
			b.badge_id=bbg.badge_id and
			bg.badge_group_id= bbg.badge_group_id and
			asbg.badge_group_id=bg.badge_group_id and
			b.rec_del_ind='N' and
			asbg.advocacy_site_id={$s} 
			union
			select * from (
				select b.badge_id,b.name,asbg.sort_order as sort_order, bg.title as title, bg.description as group_description,
				b.description_1,b.question,b.description_2,answer_1,answer_2,b.download_count,verb,
				concat('{$uploadpath}',b.badge_id,'.png') as image,
				concat('{$uploadpath}',b.badge_id,'.zip') as zip,

				if(b.external_url='',
					if(b.url_slug='',
						concat('{$badgepath}',urlencode(trim(replace(lower(b.name), '<sup>&reg;</sup>',''))),'/'),
						concat('{$badgepath}',urlencode(trim(replace(lower(b.url_slug), '<sup>&reg;</sup>',''))),'/')
					),
					urlencode(trim(replace(lower(b.external_url), '<sup>&reg;</sup>','')))
				) AS path,email

				from
				{$wpdb->prefix}badges b, 
				{$wpdb->prefix}badge_groups bg,
				{$wpdb->prefix}advocacy_site_badge_count asbc,
				{$wpdb->prefix}advocacy_site_badge_groups asbg
				
				where 
				b.rec_del_ind='N' and
				b.badge_id=asbc.badge_id and asbc.badge_id !=1 and
				bg.is_active=1 and
				bg.badge_group_id=7 and
				asbg.badge_group_id=7 and
				asbg.advocacy_site_id={$s} and
				asbc.advocacy_site_id={$s} 
				order by asbc.download_count desc, b.download_count desc limit 5
			) as popular

			order by sort_order, title,name";

			//echo $sql;
	return $wpdb->get_results($sql);
	
}


$badges=lggrababadge_db_get_badgesBySite2($_GET['currentDomain']);
$lastgroup="";
$titles=array();
$json_badges=str_replace('\/', '/',json_encode($badges));


?>
