
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app',
    data: {
    		branch:"",
    		mobile:"",
            acc_type:"",
            loan_cat:"",
            acc_num:"",
            error:"",
            hide:true,
        },
       methods:{
       		hide_it(){
       			this.hide = true;
       		},
            submit(){
            	
                if (this.acc_type=="" || this.branch =="" || this.mobile=="") {
                	this.error = 'Account type, Branch number and Mobile number field is required';
                	return false;
                }
                if (this.acc_type=="loan" && this.loan_cat=="") {
                		this.error = 'Loan category field is required';
                		return false;
                	}
                return true;
            },
            get_num(){
            	if (!this.submit()) {
            		return alert(this.error);
            	}
            	if (this.mobile.length < 5) {
            		return alert('Mobile number lenght must be more than 5');
            	}
            	if (isNaN(this.mobile)) {
            		return alert('Mobile number must be a valid number');
            	}
            	this.error = "";

            	var cat ="";
            	var mobile = this.mobile.substr(this.mobile.length - 5);
            	if (this.acc_type =='savings') {
            		cat = 101;
            	}else if (this.acc_type =='loan') {
            		cat = this.loan_cat;
            		
            	}

            	this.hide = false;
            	var acc_no = this.branch.toString() + cat.toString() + mobile.toString();
            	this.acc_num = acc_no;
            	alert(acc_no);
            }
        }
});
