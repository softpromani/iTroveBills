import{q as p,T as _,o as i,c as m,b as a,a as e,u as r,w,L as v,e as y,d as g,f as V}from"./app-7f38a0fa.js";import{a as n,b as l,_ as d}from"./TextInput-a00d3dc4.js";import{_ as x}from"./PrimaryButton-0772cd82.js";const b=a("header",null,[a("h2",{class:"text-lg font-medium text-gray-900"},"Update Password"),a("p",{class:"mt-1 text-sm text-gray-600"}," Ensure your account is using a long, random password to stay secure. ")],-1),k=["onSubmit"],P={class:"flex items-center gap-4"},S={key:0,class:"text-sm text-gray-600"},h={__name:"UpdatePasswordForm",setup(N){const u=p(null),c=p(null),s=_({current_password:"",password:"",password_confirmation:""}),f=()=>{s.put(route("password.update"),{preserveScroll:!0,onSuccess:()=>s.reset(),onError:()=>{s.errors.password&&(s.reset("password","password_confirmation"),u.value.focus()),s.errors.current_password&&(s.reset("current_password"),c.value.focus())}})};return(U,o)=>(i(),m("section",null,[b,a("form",{onSubmit:y(f,["prevent"]),class:"mt-6 space-y-6"},[a("div",null,[e(d,{for:"current_password",value:"Current Password"}),e(n,{id:"current_password",ref_key:"currentPasswordInput",ref:c,modelValue:r(s).current_password,"onUpdate:modelValue":o[0]||(o[0]=t=>r(s).current_password=t),type:"password",class:"mt-1 block w-full",autocomplete:"current-password"},null,8,["modelValue"]),e(l,{message:r(s).errors.current_password,class:"mt-2"},null,8,["message"])]),a("div",null,[e(d,{for:"password",value:"New Password"}),e(n,{id:"password",ref_key:"passwordInput",ref:u,modelValue:r(s).password,"onUpdate:modelValue":o[1]||(o[1]=t=>r(s).password=t),type:"password",class:"mt-1 block w-full",autocomplete:"new-password"},null,8,["modelValue"]),e(l,{message:r(s).errors.password,class:"mt-2"},null,8,["message"])]),a("div",null,[e(d,{for:"password_confirmation",value:"Confirm Password"}),e(n,{id:"password_confirmation",modelValue:r(s).password_confirmation,"onUpdate:modelValue":o[2]||(o[2]=t=>r(s).password_confirmation=t),type:"password",class:"mt-1 block w-full",autocomplete:"new-password"},null,8,["modelValue"]),e(l,{message:r(s).errors.password_confirmation,class:"mt-2"},null,8,["message"])]),a("div",P,[e(x,{disabled:r(s).processing},{default:w(()=>[g("Save")]),_:1},8,["disabled"]),e(v,{"enter-active-class":"transition ease-in-out","enter-from-class":"opacity-0","leave-active-class":"transition ease-in-out","leave-to-class":"opacity-0"},{default:w(()=>[r(s).recentlySuccessful?(i(),m("p",S,"Saved.")):V("",!0)]),_:1})])],40,k)]))}};export{h as default};
