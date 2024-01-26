<? require_once("../global/config.php"); 
if ($_POST['blogId']) {
	$payload = file_get_contents("https://ipgeolocation.abstractapi.com/v1/?api_key=21935d41f8d546a8b51abbe4adba30ce&ip_address=".get_ip_address());
	$json = json_decode($payload);
	$LOC  = $json->city.', '. $json->region.' - '. $json->country;
	
	$PAGE_VISIT['LOCATION'] 	= $LOC;
	$PAGE_VISIT['PAGE_TYPE'] 	= 1;
	$PAGE_VISIT['PAGE_NAME'] 	= 'Blog';
	$PAGE_VISIT['HEADING'] 		= '';
	$PAGE_VISIT['IP_ADDRESS'] 	= get_ip_address();
	$PAGE_VISIT['CREATED_ON'] 	= date("Y-m-d H:i");
	//db_perform('PAGE_VISIT', $PAGE_VISIT, 'insert');
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?=$title?> - Blog</title>

		<? require_once("css.php"); ?>
		
	</head>
	<body>
		<? require_once("menu.php"); ?>
		<main>
			<section class="container-fluid d-flex justify-content-center">
				<div class="min-hero">
					<div class="col-lg-6">
						<h1 class="hero-h1">
							News
						</h1>
						<p class="hero-p">
							<? //$res_blog = $db->Execute("SELECT INTRO_TEXT FROM PAGE_INTRO WHERE ACTIVE = '1' AND PAGE_NAME = 'White-Label' ");
							//echo $res_blog->fields['INTRO_TEXT']; ?>
						</p>
						<!--<a href="#" class="btn-read">Read More</a>-->
					</div>
					<div class="col-lg-6 img-hero">
						<img  src="<?=$http_path?>new/assets/images/undraw_Reminder_re_fe15.png" alt="" class="blog-hero-img" srcset="" />
					</div>
					<hr/ class="hr-b-hero">
				</div>
			</section>
			
			<section class="blog-content">
				<div class="card-blog">
					<span>
						<h1 class="LA-card">Latest Artical</h1>
					</span>
				</div>
				
				<div class="blog-card">
					<? $res_blog = $db->Execute("SELECT BLOG_DATA.* FROM BLOG_DATA LEFT JOIN BLOG_DATA_SITE ON BLOG_DATA_SITE.PK_BLOG_DATA = BLOG_DATA.PK_BLOG_DATA WHERE BLOG_DATA.ACTIVE = '1' AND PK_SITE = 5 ORDER BY PUBLISHED_ON DESC");
					while (!$res_blog->EOF) { ?>
					<div class="card-container">
						<div class="card-image">
							<img src="<?=$http_path.($res_blog->fields['THUMBNAIL_IMAGE'] != ''?str_replace('../', '', $res_blog->fields['THUMBNAIL_IMAGE']):'');?>" alt="a brand new sports car" />
						</div>
						
						<div class="card-body">
						<span class="p-0 m-0"><strong><?=date('d', strtotime($res_blog->fields['PUBLISHED_ON']))?></strong> <span><?=date('M', strtotime($res_blog->fields['PUBLISHED_ON']))?></span></span>
							<h1><?=$res_blog->fields['HEADING'];?></h1>
							<div class="card-author">
								<a href="<?=$http_path?>new/news/<?=$res_blog->fields['PK_BLOG_DATA']."/".url_format($res_blog->fields['HEADING']);?>" class="card-more-btn">Read More</a>
							</div>
						</div>
					</div>
					<? $res_blog->MoveNext(); 
					} ?>
				
				</div>
			</section>
			
			<!--<section>
				<div class="exp-more">
					<h1>Explore More Articals</h1>
					<a href="#" class="btn-exp-more">Explore More</a>
				</div>
			</section>-->
			
		</main>
		<? require_once("footer.php"); ?>
		<? require_once("js.php"); ?>
		
	</body>
	
	<script>
		jQuery(document).ready(function($) {
			let blog_m = {}
			const blogId =	1;
			const date = new Date();
			//Date format MM/DD/YYYY
			const today = (((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear()); 
			try {
				blog_m = JSON.parse(localStorage.getItem('blog_m'));
			} catch (error) {
			}
			blog_m = blog_m === null  ? {} : blog_m
			consolidatVisited();
			if(!Object.keys(blog_m).includes(blogId.toString())){
				$.ajax({
					url: window.location.href,
					type: "POST",
					data: {
						blogId: blogId,
					},
					success: function(data) {
						blog_m[blogId] = today;
						localStorage.setItem('blog_m',JSON.stringify(blog_m));
					}
				})
			}
			function consolidatVisited(){
				for (var key of Object.keys(blog_m)) {
					if(today !== blog_m[key]){
						delete blog_m[key]
					}
				}
				localStorage.setItem('blog_m',JSON.stringify(blog_m));
			}
		});
	</script>
</html>
