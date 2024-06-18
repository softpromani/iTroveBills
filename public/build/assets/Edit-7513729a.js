import{T as V,o as u,c as n,a as t,u as s,w as c,F as f,Z as b,d as v,b as a,s as x,h as k,M as w,t as h,B as C,p as U,f as q}from"./app-4ca9054c.js";import{_ as N}from"./AuthenticatedLayout-3560ad74.js";import{_ as d,a as r,b as i}from"./TextInput-2a8c2521.js";import{T}from"./TextareaInput-a01fd4cd.js";import{_ as S}from"./PrimaryButton-6d1c3843.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./FlashMessages-f36c4861.js";const $={class:"container"},B={class:"row"},R={class:"col-md-12 rounded-lg bg-white shadow-md pb-3"},j={class:"mt-6 space-y-6"},A={class:"row"},D={class:"col-md-6 mt-2"},E={class:"col-md-6 mt-2"},F={class:"col-md-6 mt-2"},M={class:"col-md-6 mt-2 pt-4"},O=["value","id"],P=["for"],G={class:"col-md-6 mt-2"},I={class:"col-md-6 mt-2 hidden"},L={class:"col-md-6 mt-2"},Z={class:"col-12 mt-2"},z={class:"flex items-center gap-4"},H={key:0,class:"text-sm text-gray-600"},te={__name:"Edit",props:{edit_customers:Object,inv_tax_type:Object},setup(p){const _=p;let m=_.edit_customers.customer_detail;const e=V({name:m.name,email:m.email,mobile:m.mobile,gst:m.gst,iec:m.iec,city:m.city,pin:m.pin,address:m.address,tax_type:m.tax_type});function y(){e.post(route("company.customer.update",_.edit_customers.id))}return(J,o)=>(u(),n(f,null,[t(s(b),{title:"Customers"}),t(N,null,{header:c(()=>[v(" Seller Customers ")]),default:c(()=>[a("div",$,[a("div",B,[a("div",R,[a("form",j,[a("div",A,[a("div",D,[t(d,{for:"name",value:"Customer Name"}),t(r,{id:"name",type:"text",class:"mt-1 block w-full",modelValue:s(e).name,"onUpdate:modelValue":o[0]||(o[0]=l=>s(e).name=l),required:"",autofocus:"",autocomplete:"name"},null,8,["modelValue"]),t(i,{class:"mt-2",message:s(e).errors.name},null,8,["message"])]),a("div",E,[t(d,{for:"email",value:"Customer email"}),t(r,{id:"email",type:"text",class:"mt-1 block w-full",modelValue:s(e).email,"onUpdate:modelValue":o[1]||(o[1]=l=>s(e).email=l),required:"",autofocus:"",autocomplete:"email"},null,8,["modelValue"]),t(i,{class:"mt-2",message:s(e).errors.email},null,8,["message"])]),a("div",F,[t(d,{for:"mobile",value:"Customer mobile"}),t(r,{id:"mobile",type:"text",class:"mt-1 block w-full",modelValue:s(e).mobile,"onUpdate:modelValue":o[2]||(o[2]=l=>s(e).mobile=l),required:"",autofocus:"",autocomplete:"mobile"},null,8,["modelValue"]),t(i,{class:"mt-2",message:s(e).errors.mobile},null,8,["message"])]),a("div",M,[(u(!0),n(f,null,x(p.inv_tax_type,l=>(u(),n("div",{key:l.id,class:"form-check form-check-inline"},[k(a("input",{class:"form-check-input",type:"radio",name:"inv_tax_type",value:l.status,"onUpdate:modelValue":o[3]||(o[3]=g=>s(e).tax_type=g),id:"inlineRadio"+l.id},null,8,O),[[w,s(e).tax_type]]),a("label",{class:"form-check-label",for:"inlineRadio"+l.id},h(l.status),9,P)]))),128)),t(i,{class:"mt-2",message:s(e).errors.tax_type},null,8,["message"])]),a("div",G,[t(d,{for:"gst",value:"Customer GST/PAN/TAN"}),t(r,{id:"gst",type:"text",class:"mt-1 block w-full",modelValue:s(e).gst,"onUpdate:modelValue":o[4]||(o[4]=l=>s(e).gst=l),required:"",autofocus:"",autocomplete:"gst"},null,8,["modelValue"]),t(i,{class:"mt-2",message:s(e).errors.gst},null,8,["message"])]),a("div",I,[t(d,{for:"city",value:"Customer City"}),t(r,{id:"city",type:"text",class:"mt-1 block w-full",modelValue:s(e).city,"onUpdate:modelValue":o[5]||(o[5]=l=>s(e).city=l),required:"",autofocus:"",autocomplete:"city"},null,8,["modelValue"]),t(i,{class:"mt-2",message:s(e).errors.city},null,8,["message"])]),a("div",L,[t(d,{for:"pin",value:"Customer pin"}),t(r,{id:"pin",type:"text",class:"mt-1 block w-full",modelValue:s(e).pin,"onUpdate:modelValue":o[6]||(o[6]=l=>s(e).pin=l),required:"",autofocus:"",autocomplete:"pin"},null,8,["modelValue"]),t(i,{class:"mt-2",message:s(e).errors.pin},null,8,["message"])]),a("div",Z,[t(d,{for:"address",value:"Customer address"}),t(T,{id:"address",type:"text",class:"mt-1 block w-full",modelValue:s(e).address,"onUpdate:modelValue":o[7]||(o[7]=l=>s(e).address=l),required:"",autofocus:"",autocomplete:"address"},null,8,["modelValue"]),t(i,{class:"mt-2",message:s(e).errors.address},null,8,["message"])])]),a("div",z,[t(S,C({onClick:y},s(e).processing),{default:c(()=>[v("Save")]),_:1},16),t(U,{"enter-active-class":"transition ease-in-out","enter-from-class":"opacity-0","leave-active-class":"transition ease-in-out","leave-to-class":"opacity-0"},{default:c(()=>[s(e).recentlySuccessful?(u(),n("p",H," Saved. ")):q("",!0)]),_:1})])])])])])]),_:1})],64))}};export{te as default};