<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */
$doc = JFactory::getDocument();
$doc->addScript(JUri::root().'components/com_bliss/media/js/vue.js');

?>
<h1>Vue playground</h1>

<div id="app">
	<div class="main-box">
		<h3>hello: {{ fullName }} </h3>

		<p v-bind:title='myTitle'>foo</p>

		<input type="text" name="firstName" v-model="firstName" />
		<input type="text" name="lastName" v-model="lastName" />
	</div>
	<select name="state" id="state" v-model="state">
		<option value="1">open</option>
		<option value="0">close</option>
	</select>
	<button type="button" class="btn" v-on:click="state=1">open</button>
	<button type="button" class="btn" v-on:click="state=0">close</button>
</div>


<script>
	var vm = new Vue ({
	    el:'#app',
		data:{flower:'sakura',
			myTitle:'Hello',
			myClass:'para',
            firstName:'abcder',
            lastName:'Lee',
            state:'0'
		},
		methods:{
	        getTitleName: function () {
		        return this.myTitle+'Hello world';
            },
            toggleMainBox:function(value){
                if(value==1){
                    jQuery('.main-box').slideDown();
                }else{
                    jQuery('.main-box').slideUp();
                }
            }
		},
		mounted:function(){
	        this.toggleMainBox(this.state);
		},
		computed:{
		        fullName:function(){
		            return this.firstName+" "+this.lastName;
		        }
		},
		watch:{
	        state:function(value,oldValue){
                this.toggleMainBox(value);

	            console.log(value, oldValue);
	        }
		}

	})
</script>