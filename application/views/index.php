<!doctype html>
<html class="fixed sidebar-left-collapsed">
	<head>
        <?php
        $title = "U-Dashboard";
        include 'partials/head.php';
        ?>
		<style type="text/css">
            <?php
            foreach ($types as $type){
                $color = dechex(hexdec($type['color']) + 60);
                ?>
                body .btn-info:hover.<?php echo($type['name']);?> {
                	background-color: #<?php echo($color);?>;
                	border-color: #<?php echo($color);?> !important;
				}

                body .btn-info.<?php echo($type['name']);?> {
					background-color: <?php echo($type['color']);?>;
					border-color: <?php echo($type['color']);?> !important;
				}
			<?php
            }
            ?>

        </style>
	</head>
	<body>
		<section class="body">

        <?php include 'partials/header_tmpl.php'; ?>

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<?php
				$navData=[];
				$first = true;
				foreach ($types as $type){
					if ($first){
						$icon = 'fa fa-university';
						$first = false;
					}
					else
						$icon = "el-icon-group";
					$navData[] = ['url'=>'inicio?sector='.$type['name'], 'name'=>ucwords($type['name']), 'icon'=>$icon];
				}
				include 'partials/navigation.php';
				?>
				<!-- end: sidebar -->

				<section role="main" class="content-body">
					<header class="page-header">
						<h2>U-Dashboard</h2>

						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="<?php echo base_url();?>inicio">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span><?php echo($name);?></span></li>
							</ol>
							<label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
						</div>
					</header>
					<?php
						$dept_id=1;

						if($name == "Soporte"){
							$dept_id=0;
						}
					?>
					<div class="pane panel-transparent">
						<header class="panel-heading">
							<h2 class="panel-title"><button onclick='changePage(<?php echo $dept_id?>)' class="mb-xs mt-xs mr-xs btn btn-primary btn-lg btn-block"> DCC </button></h2>
						</header>
						<div class="panel-body">
							<?php
						    $counter = 0;
						    foreach ($areaunit as $au){
						        $kind = false;
						        $color = false;
						        foreach ($types as $type){
						            if ($type['id']==$au['area']->getType()){
						                $kind = $type['name'];
						                $color = $type['color'];
						            }
						        }
								if ($counter % 2 == 0)
									echo ('<div class="row">');
							?>
						        <div class="col-md-6">
						        	<section class="panel panel-info">
						        		<header class="panel-heading" style="background-color: <?php echo $color?>">
						        			<h2 class="panel-title">
												<button onclick='changePage(<?php echo $au['area']->getId()?>)' class="mb-xs mt-xs mr-xs btn btn-info btn-lg btn-block <?php echo $kind?> "><?php echo ucwords($au['area']->getName())?></button>
											</h2>
										</header>
						        		<div class="panel-body">
											<div class="btn-group-vertical col-md-12">
												<?php
												foreach ($au['unidades'] as $unidad){
													echo('<button class="btn btn-default" onclick="changePage('.$unidad->getId().')">'.ucwords($unidad->getName()).'</button>');
												}
												?>
						        			</div>
										</div>
									</section>
								</div>
						    <?php
								if ($counter % 2 != 0)
									echo ('</div>');
								$counter++;
						    } ?>
						</div>
					</div>
					<!-- end: page -->
				</section>
			</div>

		</section>

		<?php include 'partials/footer.php'; ?>

		<script type="text/javascript">
			function changePage(org){
      			window.location.href = "<?php echo base_url();?>dashboard?org=" + org;
    		}
		</script>

	</body>
</html>
