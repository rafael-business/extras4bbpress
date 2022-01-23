(function( $ ) {
	'use strict';

	$( document ).ready( function(){
		tinymce.init( {
			mode : "exact",
			elements : ['relatorio_resumo', 'relatorio_parecer'],
			theme: "modern",
			skin: "lightgray",
			menubar : false,
			statusbar : false,
			toolbar: [
				"bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | undo redo"
			],
			plugins : "paste",
			paste_auto_cleanup_on_paste : true,
			paste_postprocess : function( pl, o ) {
				o.node.innerHTML = o.node.innerHTML.replace( /&nbsp;+/ig, " " );
			}
		});

		$( document ).on( 'change', '.resposta', function(){

			var respostas = [];
			$('.resposta').map( function(){

				if ( !$(this).is(':checked') ) return false;
				respostas.push($(this).val());
			});

			var badge = $('#badge_respostas');
			if ( 0 === respostas.length ){

				badge.text( 'Nenhuma' );
				badge.removeClass('bg-success');
				badge.addClass('bg-danger');
			} else {

				badge.text( respostas.length );
				badge.removeClass('bg-danger');
				badge.addClass('bg-success');
			}

			$('#respostas').val( respostas );
		});

		$( document ).on( 'click', '#gerar_relatorio', function(e){

			e.preventDefault();
			const dados = {
				gestor: $('#relatorio_gestor').val(),
				topic_id: $('#relatorio_topic_id').val(),
				topic: $('#relatorio_topic').val(),
				topic_tags: $('#relatorio_topic_tags').val(),
				respostas: $('#respostas').val(),
				resumo: tinymce.get('relatorio_resumo').getContent(),
				parecer: tinymce.get('relatorio_parecer').getContent(),
				etiqueta: $('input[name="relatorio_etiqueta"]:checked').val(),
				parent: $('#relatorio_parent').val()
			};

			const url_save = $(this).data('save');

			$.post( url_save, dados )
			.done(function( data ) {

				var code = JSON.parse( data ).code;
				var message = JSON.parse( data ).message;
				var link = 'success' === code ? JSON.parse( data ).link : null;

				$('.results .alert').hide();
				$('#result_'+code).show();
				$('#result_'+code).text( message );
				$('.results').show();

				setTimeout(() => {
					if ( link ) {window.open( link )};
					document.location.reload(true);
				}, 1000);
			});
		});
	});


	$(document).on( 'change', '#relatorio_encerrar', () => $('#gerador').toggle());
})( jQuery );
