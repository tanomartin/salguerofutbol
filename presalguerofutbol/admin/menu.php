<script>

	function ir(menu, _pag, pag_menu) {
		document.frm_mainmenu.menu.value = menu;
		document.frm_mainmenu._pag.value = _pag;	
		document.frm_mainmenu.pag_submenu.value = pag_menu;	
		document.frm_mainmenu.pag_menu.value = pag_menu;	
		document.frm_mainmenu.action = pag_menu;
		document.frm_mainmenu.submit();	
	}

	function ir1(menu,submenu, _pag, pag_menu,pag_submenu) {
		document.frm_mainmenu.menu.value = menu;	
		document.frm_mainmenu.submenu.value = submenu;	
		document.frm_mainmenu._pag.value = _pag;	
		document.frm_mainmenu.pag_submenu.value = pag_menu;	
		document.frm_mainmenu.pag_menu.value = pag_menu;	
		document.frm_mainmenu.action = pag_submenu;
		document.frm_mainmenu.submit();
	}

</script>

<form name="frm_mainmenu" action="" method="post">
    <input name="menu" value="" type="hidden" />
    <input name="_pag" value="" type="hidden" />
    <input name="submenu" value="" type="hidden" />
    <input name="pag_submenu" value="" type="hidden"/>
    <input name="pag_menu" value="" type="hidden"/>
</form>
<div id="logo">
	<a href="paneldecontrol.php" title="Volver al incio"><h1> Salguero Futbol</h1>
	</a>
</div>
<!-- indexer::stop -->
<div class="mod_navigation block" id="mainmenu">
<a href="#skipNavigation1" class="invisible">Skip navigation</a>
<ul class="level_1">
	<li class="<? if( $_POST["menu"] == "Inicio") echo "active"; ?> home first" onclick="this.blur(); ir('Panel de Control', '', 'paneldecontrol.php')" >
    	<span class=" home first" style="cursor:pointer">Inicio</span>
    </li>
    
	<? if (strpos($_SERVER['PHP_SELF'], "index.php") == 0 ) {?>
	<li class="<? if( $_POST["menu"]== "Secciones") echo "active"; ?>  submenu "><span class="submenu">Conf. del Torneo</span>
	  <ul style="visibility: visible; opacity: 1;" class="level_2">
	    <li class="first">
	      <a href="#" title="Torneos"  class="first"  onclick="this.blur(); ir('Torneos', '', 'torneos.php', 'torneos.php')">Torneos</a>
        </li>
		<li>
	      <a href="#" title="Zonas" class="first" onclick="this.blur(); ir('Zonas','','zonas.php','zonas.php')">Zonas</a>
        </li>
	    <li>
	      <a href="#" title="Equipos" class="first" onclick="this.blur(); ir('Equipos', '', 'equipos.php', 'equipos.php')">Equipos</a>
        </li>
	    <li>
	      <a href="#" title="Fechas" class="first" onclick="this.blur(); ir('Fechas', '', 'fechas.php', 'fechas.php')">Fechas</a>
        </li>        
      </ul>
    </li> 
	
	<li class=<? if( $menu == "Parametros") echo "active"; ?> "submenu"><span class="submenu">Par&aacute;metros</span>
	  <ul style="visibility: visible; opacity: 1;" class="level_2">
	    <li class="first">
	      <a href="#" title="Sedes" class="first" onclick="this.blur(); ir('Sedes', '', 'sedes.php', 'sedes.php')">Sedes</a>
        </li>      
      </ul>
    </li>
	
	<li class="<? if( $_POST["menu"]== "Gestion") echo "active"; ?>  submenu "><span class="submenu">Gesti&oacute;n del Torneo</span>
	  <ul style="visibility: visible; opacity: 1;" class="level_2">
	    <li class="first">
	      <a href="#" title="Partidos"  class="first"  onclick="this.blur(); ir('Fixture', '', 'fixture.php', 'fixture.php')">Partidos</a>
        </li>   
		<li>
	      <a href="#" title="Goleadores" class="first"  onclick="this.blur(); ir('Goleadores', '', 'goleadores.php', 'goleadores.php')">Goleadores</a>
        </li>       
      </ul>
    </li> 
	
 <? } ?> 
</ul>
<a name="skipNavigation1" id="skipNavigation1" class="invisible">&nbsp;</a>
</div>
<!-- indexer::continue -->