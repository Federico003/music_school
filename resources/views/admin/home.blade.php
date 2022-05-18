@extends('layouts.admin.app')

@section('content')
<div id="app">
    <div class="container-fluid">
        <div class="block-header">
            <h2>@{{ title }}
                <small>@{{ smallTitle }}</small>
            </h2>
        </div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
	var app = new Vue({
	
        // Eleneto che identenfica l'App
		el: '#app',
		
		// Modello
		data: {
			title: '',
			smallTitle: 'Dashboard principale'
		},
		
		mounted() {
			this.startup();
		}, 

		// Metodi
		methods: {
			/** 
			 * Example 
			 */
			startup:function() {
                this.title = "DASHBOARD";
            },
		}
	});
</script>
@endsection
