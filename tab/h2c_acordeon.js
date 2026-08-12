// JavaScript Document

jQuery.fn.h2c_acordeon = function() {
	var div_id = jQuery(this).attr('id');
	var div = jQuery(this);
	$("#" + div_id).addClass('aco_contenedor');
	$("#" + div_id + ">h1").addClass('aco_titulo');
	$("#" + div_id + ">div").addClass('aco_contenido');
	$("#" + div_id + ">div").hide();
	$("#" + div_id + ">h1").click(function(){
		$(this).next('div').slideToggle("fast");
	}).mouseover(function(){
		$(this).addClass('aco_foco');
	}).mouseout(function(){
		$(this).removeClass('aco_foco');
	});

}