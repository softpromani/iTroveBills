import{T as f,r as w,o as _,c as g,a as s,u as o,w as l,F as V,Z as b,b as r,n as k,d as v,e as x}from"./app-7f38a0fa.js";import{A as y}from"./ApplicationLogo-b9e0fc2e.js";import{_ as P}from"./GuestLayout-690c2d02.js";import{_ as i,a as m,b as n}from"./TextInput-a00d3dc4.js";import{_ as $}from"./PrimaryButton-0772cd82.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./FlashMessages-3c3d7e2a.js";/* empty css                                                                      */const h=["onSubmit"],C={class:"mt-3"},S={class:"mt-3"},q={class:"mt-4 flex items-center justify-end"},T={__name:"ResetPassword",props:{email:String,token:String},setup(p){const d=p,e=f({token:d.token,email:d.email,password:"",password_confirmation:""}),u=()=>{e.post(route("password.update"),{onFinish:()=>e.reset("password","password_confirmation")})};return(B,a)=>{const c=w("Link");return _(),g(V,null,[s(o(b),{title:"Reset Password"}),s(P,null,{default:l(()=>[s(c,{href:"/",class:"mb-4 flex items-center justify-center"},{default:l(()=>[s(y,{class:"h-20 w-20 fill-current text-gray-500"})]),_:1}),r("form",{onSubmit:x(u,["prevent"])},[r("div",null,[s(i,{for:"email",value:"Email"}),s(m,{id:"email",type:"email",class:"mt-1 block w-full",modelValue:o(e).email,"onUpdate:modelValue":a[0]||(a[0]=t=>o(e).email=t),required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),s(n,{class:"mt-2",message:o(e).errors.email},null,8,["message"])]),r("div",C,[s(i,{for:"password",value:"Password"}),s(m,{id:"password",type:"password",class:"mt-1 block w-full",modelValue:o(e).password,"onUpdate:modelValue":a[1]||(a[1]=t=>o(e).password=t),required:"",autocomplete:"new-password"},null,8,["modelValue"]),s(n,{class:"mt-2",message:o(e).errors.password},null,8,["message"])]),r("div",S,[s(i,{for:"password_confirmation",value:"Confirm Password"}),s(m,{id:"password_confirmation",type:"password",class:"mt-1 block w-full",modelValue:o(e).password_confirmation,"onUpdate:modelValue":a[2]||(a[2]=t=>o(e).password_confirmation=t),required:"",autocomplete:"new-password"},null,8,["modelValue"]),s(n,{class:"mt-2",message:o(e).errors.password_confirmation},null,8,["message"])]),r("div",q,[s($,{class:k(["w-full",{"opacity-25":o(e).processing}]),disabled:o(e).processing},{default:l(()=>[v(" Reset Password ")]),_:1},8,["class","disabled"])])],40,h)]),_:1})],64)}}};export{T as default};
