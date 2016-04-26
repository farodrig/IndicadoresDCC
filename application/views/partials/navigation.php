<aside id="sidebar-left" class="sidebar-left">

	<div class="sidebar-header">
		<div class="sidebar-title">Navegación</div>
		<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
			<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
		</div>
	</div>

	<div class="nano">
		<div class="nano-content">
			<nav id="menu" class="nav-main" role="navigation">
				<ul class="nav nav-main">
					<?php
						if(isset($foda) && $foda){
                            array_unshift($navData, ['url'=>'fodaStrategy', 'name'=>'FODAs y Objetivos Estratégicos', 'icon'=>'fa fa-book']);
						}
						if(isset($budget) && $budget){
                            array_unshift($navData, ['url'=>'presupuesto', 'name'=>'Presupuesto', 'icon'=>'fa fa-money']);
						}
                        array_unshift($navData, ['url'=>'inicio', 'name'=>'U-Dashboard', 'icon'=>'fa fa-home']);
						foreach ($navData as $navItem) {
					?>
							<li>
								<a href="<?php echo base_url().$navItem['url'];?>">
									<i class="<?php echo $navItem['icon'];?>" aria-hidden="true"></i>
									<span><?php echo $navItem['name'];?></span>
								</a>
							</li>
					<?php
						}
					?>
				</ul>
			</nav>
		</div>
	</div>

</aside>