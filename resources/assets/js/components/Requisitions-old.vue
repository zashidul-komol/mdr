<template>
    <div>
        <div class="row">
            <div class="col-md-10 text-right mb-sm">
                <span v-if="inputs.length">Total Items type: {{inputs.length}} and Total Items: {{stock}}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 text-center mb-sm">Product Name 
            </div>
            <div class="col-md-2 text-center mb-sm">For Whoom 
            </div>
            <div class="col-md-1 text-right mb-sm">For Stock 
            </div>
            <div class="col-md-2 text-center mb-sm">Quantity 
            </div>
            <div class="col-md-1 text-right mb-sm">P. Stock 
            </div>
            <div class="col-md-2 text-left mb-sm">&nbsp&nbsp&nbsp&nbsp&nbspRemarks 
            </div>
            <div class="col-md-2 text-center mb-sm"> 
            </div>
        </div>

        <ul id="stock-add">
          <li v-for="(input, index) in inputs" class="mb-sm">
             <input v-if="input.id" class="hidden" :name="'details['+index+'][id]'" :value="input.id"></input>
            <div class="row">
                <div class="col-md-2">
                     <select class="form-control" :name="'details['+index+'][product_id]'" v-model="input.product_id" @change="getProductParticulars($event)">
                        <option v-if="input.product_id==null" value="null">Select Product</option>
                        <option v-else value="">Select Product</option>
                        <option v-for="(val,key) in products" v-bind:value="key">
                            {{ val }}
                        </option>
                     </select>

                </div>
                <div class="col-md-2">
                     <select class="form-control"
                       :name="'details['+index+'][particular_id]'" v-model="input.particular_id">
                        <option value="">Select For Whoom</option>
                        <option v-for="(val,key) in particulars" v-bind:value="key">
                            {{ val }}
                        </option>
                     </select>
                </div>
                <div class="col-md-1">
                    <input class="form-control" type="checkbox" id="stock" value="1" :name="'details['+index+'][stock]'" v-model="input.stock">
                </div>
                <div class="col-md-2">
                    <input class="col-md-1 form-control" type="number" placeholder="Input Quantity" :name="'details['+index+'][requsition_quantity]'" v-model="input.requsition_quantity" @change="countTotal" min="0" required="required">
                </div>
                <div class="col-md-1">
                    <input class="col-md-2 form-control" type="text" placeholder="Stock" :name="'details['+index+'][PresentStock]'" v-model="input.PresentStock" min="0" required="required" >
                </div>
                 <div class="col-md-2">
                    <input class="col-md-3 form-control" type="text" placeholder="Remarks" :name="'details['+index+'][remarks]'" v-model="input.remarks" min="0">

                </div>
                <div class="col-md-2">
                    <button v-if="index" class="btn btn-danger" @click="deleteRow(index,$event);">Delete</button>
                </div>
            </div>
          </li>
        </ul>
        <div class="row">
            <div class="col-md-offset-10 col-md-2">
                 <button class="btn btn-success" @click="addRow">Add More</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
         data() {
            return {
                inputs:[{
                    product_id: '',
                    particular_id: '',
                    stock: '',
                    requsition_quantity: '',
                    remarks: '',
                    particular_type:'',                    
                }],
                stock:0,
                particulars:{},
            }
        },
        mounted() {
            if(this.details.length){
                this.inputs=this.details;
                this.countTotal();
            }
        },
         props: {
           products: Object,
           sizes: Object,
           details: Array
        },
        methods: {
            addRow(event) {
              event.preventDefault();
              this.inputs.push({
                    product_id: '',
                    particular_id: '',
                    stock: '',
                    requsition_quantity: '',
                    remarks: '',
                    particular_type:'',
                });
            },

            countTotal(){
                let add=0;
               this.inputs.forEach(function (value, key) {
                    if (!isNaN(parseFloat(value.requsition_quantity)) && isFinite(value.requsition_quantity)) {
                        add += parseInt(value.requsition_quantity);
                    }
               });
               this.stock=add;
            },

            getProductParticulars(event) {
                event.preventDefault();
   
                axios.get('/products/get-product-tag/'+event.target.value)
                     .then((response)=>{
                     console.log(response.data);
                       this.particulars = response.data.particulars;
                       this.inputs.particular_type = response.data.particular_type;
                     }).catch(function (error) {
                        console.log(error);
                      });
            },

            deleteRow(index,event) {
                event.preventDefault();
                this.inputs.splice(index,1);
                this.countTotal();
            }
        }
}
</script>
<style>
    #stock-add{
        list-style: none;
    }
</style>
