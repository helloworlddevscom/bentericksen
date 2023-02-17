		$("#global_search").on( "keyup", function () {
			table
				.search( this.value )
				.draw();
		});
	
		$("#col_1_search").on( "keyup", function () {
			console.log( this.value );
			table
				.columns( 0 )
				.cache('search')
				.search( this.value )
				.draw();
		});
		$("#col_2_search").on( "keyup", function () {
			table
				.columns( 1 )
				.search( this.value )
				.draw();
		});
		$("#col_3_search").on( "keyup", function () {
			table
				.columns( 2 )
				.search( this.value )
				.draw();
		});
		$("#col_4_search").on( "keyup", function () {
			table
				.columns( 3 )
				.search( this.value )
				.draw();
		});
		$("#col_5_search").on( "keyup", function () {
			table
				.columns( 4 )
				.search( this.value )
				.draw();
		});
		$("#col_6_search").on( "keyup", function () {
			table
				.columns( 5 )
				.search( this.value )
				.draw();
		});
		
	/*Column searches: Selects*/
		$("#col_1_select_search").on( "change", function () {
			if ( $(this).val() == "" ) {
				table
					.columns( 0 )
					.search( $(this).val() )
					.draw();
			} else {
				table
					.columns( 0 )
					.search( "^" + $(this).val() + "$", true, false, true )
					.draw();
			}
		});
		$("#col_2_select_search").on( "change", function () {
			if ( $(this).val() == "" ) {
				table
					.columns( 1 )
					.search( $(this).val() )
					.draw();
			} else {
				table
					.columns( 1 )
					.search( "^" + $(this).val() + "$", true, false, true )
					.draw();
			}
		});
		$("#col_3_select_search").on( "change", function () {
			table
				.columns( 2 )
				.search( $(this).val() )
				.draw();
		});
		$("#col_4_select_search").on( "change", function () {
			table
				.columns( 3 )
				.search( $(this).val() )
				.draw();
		});
		$("#col_5_select_search").on( "change", function () {
			table
				.columns( 4 )
				.search( $(this).val() )
				.draw();
		});
		$("#col_6_select_search").on( "change", function () {
			if ( $(this).val() == "" ) {
				table
					.columns( 5 )
					.search( $(this).val() )
					.draw();
			} else {
				table
					.columns( 5 )
					.search( "^" + $(this).val() + "$", true, false, true )
					.draw();
			}
		});
		$("#col_7_select_search").on( "change", function () {
			if ( $(this).val() == "" ) {
				table
					.columns( 6 )
					.search( $(this).val() )
					.draw();
			} else {
				table
					.columns( 6 )
					.search( "^" + $(this).val() + "$", true, false, true )
					.draw();
			}
		});
		$("#col_8_select_search").on( "change", function () {
			if ( $(this).val() == "" ) {
				table
					.columns( 7 )
					.search( $(this).val() )
					.draw();
			} else {
				table
					.columns( 7 )
					.search( "^" + $(this).val() + "$", true, false, true )
					.draw();
			}
		});
		$("#col_9_select_search").on( "change", function () {
			table
				.columns( 8 )
				.search( $(this).val() )
				.draw();
		});
		
	/*Column searches: Multi Selects*/
		$("#col_multi_search").on( "change", function () {
			var values = $(this).val();
			if( values.length > 1){
				var re = values.join('|');
				table
					.column( 4 )
					.search( "^" + re + "$", true, true, false )
					.draw();
			}else{
				table
					.column( 4 )
					.search( values, false, true, false )
					.draw();
			}
		});
		
	/*Range filtering redraw on change*/
		if ( "{segment_2}" == "policy-list" ){
			$("#picker1, #picker2").on( "keyup", function () {
				table.draw();
			});
		} else {
			$("#picker1, #picker2").on( "change", function() {
				table.draw();
			});
		}