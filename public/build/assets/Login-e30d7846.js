import{g as b,h as v,v as k,o as n,c as d,T as y,a as t,u as s,w as i,F as V,Z as $,b as o,i as p,t as B,f,e as C,j as F,d as _,n as N}from"./app-3b5d1272.js";import{_ as U}from"./GuestLayout-f54f9568.js";import{_ as g,a as h,b as w}from"./TextInput-983235a9.js";import{_ as j}from"./PrimaryButton-cebc27a5.js";import"./FlashMessages-ea9334e5.js";import"./_plugin-vue_export-helper-c27b6911.js";const q=["value"],L={__name:"Checkbox",props:{checked:{type:[Array,Boolean],default:!1},value:{default:null}},emits:["update:checked"],setup(r,{emit:e}){const m=e,c=r,a=b({get(){return c.checked},set(l){m("update:checked",l)}});return(l,u)=>v((n(),d("input",{type:"checkbox",value:r.value,"onUpdate:modelValue":u[0]||(u[0]=x=>a.value=x),class:"text-indigo-600"},null,8,q)),[[k,a.value]])}},P={class:"row"},R=o("img",{src:"itimages/itlogo.png",alt:"",class:"h-20 text-gray-500 fill-current w-30"},null,-1),S={key:0,class:"mb-4 text-sm font-medium text-green-600"},D={class:"mt-3"},E={class:"flex justify-between mt-4"},M={class:"inline-flex items-center"},T=o("span",{class:"mx-2 text-sm text-gray-600"},"Remember me",-1),z={class:"mt-6"},K={__name:"Login",props:{canResetPassword:Boolean,status:String},setup(r){const e=y({email:"",password:"",remember:!1}),m=()=>{e.post(route("login"),{onFinish:()=>e.reset("password")})};return(c,a)=>(n(),d(V,null,[t(s($),{title:"Log in"}),t(U,null,{default:i(()=>[o("div",P,[t(s(p),{href:"/",class:"flex items-center justify-center"},{default:i(()=>[R]),_:1}),r.status?(n(),d("div",S,B(r.status),1)):f("",!0),o("form",{onSubmit:C(m,["prevent"])},[o("div",null,[t(g,{for:"email",value:"Email"}),t(h,{id:"email",type:"email",class:"block w-full mt-1",modelValue:s(e).email,"onUpdate:modelValue":a[0]||(a[0]=l=>s(e).email=l),required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),t(w,{class:"mt-2",message:s(e).errors.email},null,8,["message"])]),o("div",D,[t(g,{for:"password",value:"Password"}),t(h,{id:"password",type:"password",class:"block w-full mt-1",modelValue:s(e).password,"onUpdate:modelValue":a[1]||(a[1]=l=>s(e).password=l),required:"",autocomplete:"current-password"},null,8,["modelValue"]),t(w,{class:"mt-2",message:s(e).errors.password},null,8,["message"])]),o("div",E,[o("label",M,[t(L,{name:"remember",checked:s(e).remember,"onUpdate:checked":a[2]||(a[2]=l=>s(e).remember=l)},null,8,["checked"]),T]),r.canResetPassword?(n(),F(s(p),{key:0,href:c.route("password.request"),class:"text-sm text-gray-600 underline hover:text-gray-900"},{default:i(()=>[_(" Forgot your password? ")]),_:1},8,["href"])):f("",!0)]),o("div",z,[t(j,{class:N(["w-full",{"opacity-25":s(e).processing}]),disabled:s(e).processing},{default:i(()=>[_(" Log in ")]),_:1},8,["class","disabled"])])],32)])]),_:1})],64))}};export{K as default};