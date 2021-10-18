<?php
$panel_text_color   = !empty(config('customcss.manage_panel_text_color')) ? config('customcss.manage_panel_text_color') : '#fff';
$panel_title_color  = !empty(config('customcss.manage_panel_title_color')) ? config('customcss.manage_panel_title_color') : '#fff';
$panel_side_color   = !empty(config('customcss.manage_panel_side_color')) ? config('customcss.manage_panel_side_color') : '#333';
$panel_main_color   = !empty(config('customcss.manage_panel_main_color')) ? config('customcss.manage_panel_main_color') : '#333';
// $panel_hover_color  = !empty(config('customcss.manage_panel_hover_color')) ? config('customcss.manage_panel_hover_color') : '#DAA520';
$panel_hover_color  = '#DAA520';


?>
<style> :root { --manage-panel-main-color: <?php echo e($panel_main_color); ?>; --manage-panel-side-color: <?php echo e($panel_side_color); ?>; --manage-panel-text-color : <?php echo e($panel_text_color); ?>; --manage-panel-title-color: <?php echo e($panel_title_color); ?>; --manage-panel-hover-color: <?php echo e($panel_hover_color); ?>; } </style>