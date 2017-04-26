<h1>Hello</h1>

<?php

$doc = JFactory::getDocument();
$doc->addScript(JUri::root().'components/com_bliss/media/js/vue.js');


$app = JFactory::getApplication();
$app->setUserState('com_bliss.donation.items',[
        ['org_id'=>2, 'price'=>100],['org_id'=>3, 'price'=>300]
]);
$items= (array) $app->getUserState('com_bliss.donation.items');
//print_r($items);
?>

<div id="app">
    <div class="form-actions text-right">
        <button type="button" class="btn" v-on:click="addNewItem()">
            <span class="icon-plus"></span>
        </button>
    </div>

    <table class="table table-bordered">
        <tr v-for="(item, k) in items">
            <td>
              <!--  <label v-bind:for="getInputId(item)">{{item.id}} - {{item.title}}</label>-->
                <select v-bind:name="getInputName(k,'org_title')" id="" v-model="item.org_id">
                    <option v-for="org in orgs" v-bind:value="org.id">{{org.title}}</option>
                </select>
            </td>
            <td>
             <!--   <input id="getInputId(item)" type="text" v-model="item.price" />-->
                <input v-bind:name="getInputName(k,'price')" type="text" v-model="item.price" />
            </td>
            <td>
                <button class="btn btn-small" type="button" v-on:click="removeItem(k)">
                    <span class="icon-remove"></span>
                </button>
            </td>
        </tr>
  <!--      <li v-for="(item, k) in items">
            <label v-bind:for="getInputId(item)">{{item.id}} - {{item.title}}</label>
            <input id="getInputId(item)" type="text" v-model="item.price" />
        </li>-->
    </table>
</div>
<script>
    var vm = new Vue ({
        el: '#app',
        data: {
            orgs:[
                {id:'', title:'--Please select--'},
                {id:1,title:'Bliss'},
                {id:2,title:'Leezen'},
                {id:3,title:'TOAF'}
            ],

          /*  items: [
            {
                org_id: 1,
                price:''
            }, {
                org_id: 2,
                price:'100'

            }]*/
            items:  <?php echo json_encode($items)?>
        },
        methods: {
            getInputId:function(item){
                return 'input-'+item.id;
            },
            getInputName:function(k,name){
                return 'orderitem['+k+'] ['+name+']';
            },
            addNewItem:function(){
                this.items.push({
                    org_id:'',
                    price:''
                });

            },
            removeItem: function(k){
                   this.items.splice(k,1);
            }
        }
    })
</script>