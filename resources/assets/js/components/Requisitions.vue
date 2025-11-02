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
            <div class="col-md-2 text-center mb-sm">Required For
            </div>
            <div class="col-md-1 text-right mb-sm">Quantity 
            </div>
            <div class="col-md-1 text-right mb-sm">UOM 
            </div>
            <div class="col-md-1 text-right mb-sm">Unit Price 
            </div>
            <div class="col-md-1 text-right mb-sm">Total Price 
            </div>
            <div class="col-md-1 text-center mb-sm">P. Stock 
            </div>
            <div class="col-md-1 text-left mb-sm">&nbsp&nbsp&nbsp&nbsp&nbspRemarks 
            </div>
            <div class="col-md-2 text-center mb-sm"> 
            </div>
        </div>

        <ul id="stock-add">
          <li v-for="(input, index) in inputs" class="mb-sm">
             <input v-if="input.id" class="hidden" :name="'details['+index+'][id]'" :value="input.id"></input>
            <div class="row">
                <div class="col-md-2">
                     <select class="form-control" :name="'details['+index+'][product_id]'" v-model="input.product_id" @change="getProductParticulars($event)" required="required">
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
                    <input class="col-md-1 form-control" type="number" placeholder="Input Quantity" :name="'details['+index+'][requsition_quantity]'" v-model="input.requsition_quantity" @change="countTotal" min="0" required="required">
                </div>
                <div class="col-md-1">
                     <select class="form-control" :name="'details['+index+'][measurement_id]'" v-model="input.measurement_id" required="required">
                        <option v-if="input.measurement_id==null" value="null">Select UOM</option>
                        <option v-else value="">Select UOM</option>
                        <option v-for="(val,key) in measurements" v-bind:value="key">
                            {{ val }}
                        </option>
                     </select>

                </div>
                <div class="col-md-1">
                    <input class="col-md-2 form-control" type="text" placeholder="Unit Price" :name="'details['+index+'][unitprice]'" v-model="input.unitprice" min="0" required="required" >
                </div>
                
                <div class="col-md-1">
                    <input class="col-md-1 form-control" type="text" placeholder="Total Price" :name="'details['+index+'][totalprice]'" 
                    v-model="input.totalprice=input.requsition_quantity*input.unitprice" @change="countTotalPrice" min="0" required="required" readonly="true">
                </div>
                <div class="col-md-1">
                    <input class="col-md-1 form-control" type="number" placeholder="Stock" :name="'details['+index+'][PresentStock]'" v-model="input.PresentStock" min="0" required="required" >
                </div>
                 <div class="col-md-1">
                    <input class="col-md-3 form-control" type="textarea" placeholder="Remarks" :name="'details['+index+'][remarks]'" v-model="input.remarks" min="0">

                </div>
                <div class="col-md-1">
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
                    requsition_quantity: '',
                    measurement_id: '',
                    unitprice: '',
                    totalprice: 0,
                    remarks: '',
                    particular_type:'',                    
                }],
                stock:0,
                countPrice:0,
                particulars:{},
            }
        },
        mounted() {
            if(this.details.length){
                this.inputs=this.details;
                this.countTotal();
                this.countTotalPrice();
            }
        },
         props: {
           products: Object,
           measurements: Object,
           details: Array
        },
        methods: {
            addRow(event) {
              event.preventDefault();
              this.inputs.push({
                    product_id: '',
                    particular_id: '',
                    requsition_quantity: '',
                    measurement_id: '',
                    unitprice: '',
                    totalprice: 0,
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

            countTotalPrice(){
            console.log('count', this.countPrice);
                let TotPrice=0;
               this.inputs.forEach(function (value, key) {
                    //if (parseFloat(value.totalprice))) {
                        TotPrice += 1000;
                   // }
               });
               console.log(TotPrice);
               this.countPrice=TotPrice;
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
