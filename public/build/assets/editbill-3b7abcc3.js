import{m as v,T as b,o as r,c as _,a as e,u as t,w as p,F as V,Z as h,b as o,p as w,f as g}from"./app-b1d25c35.js";import{_ as x}from"./AuthenticatedLayout-294692cc.js";import y from"./Editbilldata-c04923b2.js";import{_ as d,a as m,b as u}from"./TextInput-88582927.js";/* empty css                                                       */import"./_plugin-vue_export-helper-c27b6911.js";import"./FlashMessages-fba8bf27.js";/* empty css                                                                      */const k={class:"container-fluid"},N={class:"row"},q=o("h2",{class:"font-medium text-center text-white uppercase font-weight-bolder"}," Itrove Bills ",-1),C=o("i",{class:"fa-solid fa-bullseye"},null,-1),U=[C],I={key:0,class:"card"},$={action:"",method:"post"},B={class:"p-2 row"},T={class:"mt-2 col-md-6"},D={class:"mt-2 col-md-6"},E={class:"mt-2 col-md-6"},F={class:"mt-2 col-md-6"},O={class:"mt-2 col-md-6"},j={class:"mt-2 col-md-6"},A={class:"mt-2 col-md-6"},Q={__name:"editbill",props:{edit_data:Object},setup(c){const i=c,n=v(!0),f=()=>{n.value=!n.value},l=b({invoice_no:i.edit_data.invoice_number,gst:i.edit_data.customer.gstin,email:i.edit_data.customer.email,address:i.edit_data.customer.address,mobile:i.edit_data.customer.mobile,vehical_no:i.edit_data.vehicle_no,customer_name:i.edit_data.customer.company_name});return(M,s)=>(r(),_(V,null,[e(t(h),{title:"Company"}),e(x,null,{default:p(()=>[o("section",k,[o("div",N,[o("div",{class:"mt-2 -mb-2 bg-purple-700 rounded-md position-relative"},[q,o("span",{class:"text-white cursor-pointer top-2 right-2 position-absolute",onClick:f},U)]),e(w,{name:"slide-fade"},{default:p(()=>[n.value?(r(),_("div",I,[o("form",$,[o("div",B,[o("div",T,[e(d,{for:"invoice_no",value:"Invoice No*"}),e(m,{id:"invoice_no",type:"text",disabled:"",modelValue:t(l).invoice_no,"onUpdate:modelValue":s[0]||(s[0]=a=>t(l).invoice_no=a),class:"block w-full mt-1",required:"",autofocus:"",autocomplete:"invoice_no"},null,8,["modelValue"]),e(u,{class:"mt-2"})]),o("div",D,[e(d,{for:"customer_name",value:"Customer Name*"}),e(m,{id:"customer_name",type:"text",disabled:"",modelValue:t(l).customer_name,"onUpdate:modelValue":s[1]||(s[1]=a=>t(l).customer_name=a),class:"block w-full mt-1",required:"",autofocus:"",autocomplete:"customer_name"},null,8,["modelValue"]),e(u,{class:"mt-2"})]),o("div",E,[e(d,{for:"mobile",value:"Mobile No"}),e(m,{id:"mobile",type:"text",class:"block w-full mt-1",disabled:"",modelValue:t(l).mobile,"onUpdate:modelValue":s[2]||(s[2]=a=>t(l).mobile=a),required:"",autofocus:"",autocomplete:"mobile"},null,8,["modelValue"]),e(u,{class:"mt-2"})]),o("div",F,[e(d,{for:"address",value:"Address*"}),e(m,{id:"address",type:"text",class:"block w-full mt-1",disabled:"",required:"",modelValue:t(l).address,"onUpdate:modelValue":s[3]||(s[3]=a=>t(l).address=a),autofocus:"",autocomplete:"address"},null,8,["modelValue"]),e(u,{class:"mt-2"})]),o("div",O,[e(d,{for:"email",value:"Email ID*"}),e(m,{id:"email",type:"text",disabled:"",modelValue:t(l).email,"onUpdate:modelValue":s[4]||(s[4]=a=>t(l).email=a),class:"block w-full mt-1",required:"",autofocus:"",autocomplete:"email"},null,8,["modelValue"]),e(u,{class:"mt-2"})]),o("div",j,[e(d,{for:"gst",value:"ID/ TPIN/ TIN NO*"}),e(m,{id:"gst",type:"text",disabled:"",modelValue:t(l).gst,"onUpdate:modelValue":s[5]||(s[5]=a=>t(l).gst=a),class:"block w-full mt-1",required:"",autofocus:"",autocomplete:"gst"},null,8,["modelValue"]),e(u,{class:"mt-2"})]),o("div",A,[e(d,{for:"vehical_no",value:"Vehicle No*"}),e(m,{id:"vehical_no",type:"text",class:"block w-full mt-1",required:"",modelValue:t(l).vehical_no,"onUpdate:modelValue":s[6]||(s[6]=a=>t(l).vehical_no=a),autofocus:"",autocomplete:"vehical_no"},null,8,["modelValue"]),e(u,{class:"mt-2"})])])])])):g("",!0)]),_:1})])]),o("div",null,[e(y,{invoiceitem:c.edit_data.invoiceitems},null,8,["invoiceitem"])])]),_:1})],64))}};export{Q as default};
