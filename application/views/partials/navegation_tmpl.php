<div class="nano has-scrollbar">
  <div class="nano-content" tabindex="0" style="right: -12px;">
    <nav id="menu" class="nav-main" role="navigation">
      <ul class="nav nav-main">
        <li>
          <a href="<?php echo base_url();?>inicio">
            <i class="fa fa-home" aria-hidden="true"></i>
            <span>U-Dashboard</span>
          </a>
        </li>
        <?php
        if($add_data==1){ ?>
        <li>
          <a href="<?php echo base_url();?>formAgregarDato?var=<?php echo $id_location ?>">
            <span class="pull-right label label-primary"></span>
            <i class="fa fa-plus-square" aria-hidden="true"></i>
            <span>Añadir Datos</span>
          </a>
        </li>
      <?php }
        ?>
      </ul>
    </nav>
  </div>
  <div class="nano-pane" style="display: none; opacity: 1; visibility: visible;">
    <div class="nano-slider" style="height: 781px; transform: translate(0px, 0px);"></div>
  </div>
</div>
