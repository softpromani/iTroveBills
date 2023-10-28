import{Q as g,T as k,o as r,c,b as o,a,u as s,f as u,t as p,w as _,K as v,e as y,d as V}from"./app-e312a1ff.js";import{a as m,b as i,_ as d}from"./TextInput-7f727ad3.js";import{_ as x}from"./PrimaryButton-42491f82.js";const w={class:"container"},U=o("header",null,[o("h2",{class:"text-lg font-medium text-gray-900"},"Firm Information")],-1),q=["onSubmit"],S={class:"row"},C={class:"mt-2 col-md-6"},h={class:"mt-2 col-md-6"},B={class:"mt-2 col-md-6"},N={class:"mt-2 col-md-6"},I={class:"mt-2 col-md-6"},F={class:"mt-2 col-md-6"},T={class:"mt-2 col-md-6"},$={class:"mt-2 col-md-6"},A={class:"mt-2 col-md-6"},E={class:"mt-2 col-md-6"},z={class:"mt-2 col-md-6"},D={class:"mt-2 col-md-6"},K={class:"mt-2 col-md-6"},M={class:"mt-2 col-md-6"},j={class:"mt-3 col-md-6"},G=["src"],L={class:"mt-3 col-md-6"},O=["value"],P=["src"],Q={class:"flex items-center gap-4"},H={key:0,class:"text-sm text-gray-600"},Y={__name:"CreateCompany",props:{editdata:Object},setup(b){const n=b;g().props.auth.user;const e=k({company_name:n.editdata.company_name??"",email:n.editdata.email??"",mobile:n.editdata.mobile??"",logo:n.editdata.logo??"",sign:n.editdata.sign??"",address:n.editdata.address??"",city:n.editdata.city??"",pin:n.editdata.pin??"",gstin:n.editdata.gstin??"",iec:n.editdata.iec??"",invoice_series:n.editdata.invoice_series??"",bank_name:n.editdata.bank_name??"",bank_branch:n.editdata.bank_branch??"",bank_ifsc:n.editdata.bank_ifsc??"",bank_account_no:n.editdata.bank_account_no??"",adcode:n.editdata.ad_code??""});function f(){n.editdata?e.post(route("company.update",n.editdata.id),{preserveScroll:!0,onSuccess:()=>e.reset()}):e.post(route("company.store"),{preserveScroll:!0,onSuccess:()=>e.reset()})}return(J,l)=>(r(),c("section",w,[U,o("form",{onSubmit:y(f,["prevent"]),class:"mt-6 space-y-6"},[o("div",S,[o("div",C,[a(d,{for:"company_name",value:"Firm Name"}),a(m,{id:"company_name",type:"text",class:"block w-full mt-1",modelValue:s(e).company_name,"onUpdate:modelValue":l[0]||(l[0]=t=>s(e).company_name=t),required:"",autofocus:"",autocomplete:"company_name"},null,8,["modelValue"]),a(i,{class:"mt-2",message:s(e).errors.company_name},null,8,["message"])]),o("div",h,[a(d,{for:"address",value:"Address"}),a(m,{id:"address",type:"text",class:"block w-full mt-1",modelValue:s(e).address,"onUpdate:modelValue":l[1]||(l[1]=t=>s(e).address=t),required:"",autofocus:"",autocomplete:"address"},null,8,["modelValue"]),a(i,{class:"mt-2",message:s(e).errors.address},null,8,["message"])]),o("div",B,[a(d,{for:"mobile",value:"Mobile"}),a(m,{id:"mobile",type:"number",class:"block w-full mt-1",modelValue:s(e).mobile,"onUpdate:modelValue":l[2]||(l[2]=t=>s(e).mobile=t),required:"",autofocus:"",autocomplete:"mobile"},null,8,["modelValue"]),a(i,{class:"mt-2",message:s(e).errors.mobile},null,8,["message"])]),o("div",N,[a(d,{for:"email",value:"Email"}),a(m,{id:"email",type:"email",class:"block w-full mt-1",modelValue:s(e).email,"onUpdate:modelValue":l[3]||(l[3]=t=>s(e).email=t),required:"",autocomplete:"email"},null,8,["modelValue"]),a(i,{class:"mt-2",message:s(e).errors.email},null,8,["message"])]),o("div",I,[a(d,{for:"city",value:"City"}),a(m,{id:"city",type:"text",class:"block w-full mt-1",modelValue:s(e).city,"onUpdate:modelValue":l[4]||(l[4]=t=>s(e).city=t),required:"",autocomplete:"city"},null,8,["modelValue"]),a(i,{class:"mt-2",message:s(e).errors.city},null,8,["message"])]),o("div",F,[a(d,{for:"pin",value:"Pin"}),a(m,{id:"pin",type:"text",class:"block w-full mt-1",modelValue:s(e).pin,"onUpdate:modelValue":l[5]||(l[5]=t=>s(e).pin=t),required:"",autocomplete:"pin"},null,8,["modelValue"]),a(i,{class:"mt-2",message:s(e).errors.pin},null,8,["message"])]),o("div",T,[a(d,{for:"gstin",value:"GSTIN"}),a(m,{id:"gstin",type:"text",class:"block w-full mt-1",modelValue:s(e).gstin,"onUpdate:modelValue":l[6]||(l[6]=t=>s(e).gstin=t),required:"",autocomplete:"gstin"},null,8,["modelValue"]),a(i,{class:"mt-2",message:s(e).errors.gstin},null,8,["message"])]),o("div",$,[a(d,{for:"iec",value:"IEC"}),a(m,{id:"iec",type:"text",class:"block w-full mt-1",modelValue:s(e).iec,"onUpdate:modelValue":l[7]||(l[7]=t=>s(e).iec=t),required:"",autocomplete:"iec"},null,8,["modelValue"]),a(i,{class:"mt-2",message:s(e).errors.iec},null,8,["message"])]),o("div",A,[a(d,{for:"invoice_series",value:"Invoice Series"}),a(m,{id:"invoice_series",type:"text",class:"block w-full mt-1",modelValue:s(e).invoice_series,"onUpdate:modelValue":l[8]||(l[8]=t=>s(e).invoice_series=t),required:"",autocomplete:"invoice_series"},null,8,["modelValue"]),a(i,{class:"mt-2",message:s(e).errors.invoice_series},null,8,["message"])]),o("div",E,[a(d,{for:"bank_name",value:"Bank Name"}),a(m,{id:"bank_name",type:"text",class:"block w-full mt-1",modelValue:s(e).bank_name,"onUpdate:modelValue":l[9]||(l[9]=t=>s(e).bank_name=t),required:"",autocomplete:"bank_name"},null,8,["modelValue"]),a(i,{class:"mt-2",message:s(e).errors.bank_name},null,8,["message"])]),o("div",z,[a(d,{for:"bank_branch",value:"Bank Branch"}),a(m,{id:"bank_branch",type:"text",class:"block w-full mt-1",modelValue:s(e).bank_branch,"onUpdate:modelValue":l[10]||(l[10]=t=>s(e).bank_branch=t),required:"",autocomplete:"bank_branch"},null,8,["modelValue"]),a(i,{class:"mt-2",message:s(e).errors.bank_branch},null,8,["message"])]),o("div",D,[a(d,{for:"bank_ifsc",value:"Bank IFSC"}),a(m,{id:"bank_ifsc",type:"text",class:"block w-full mt-1",modelValue:s(e).bank_ifsc,"onUpdate:modelValue":l[11]||(l[11]=t=>s(e).bank_ifsc=t),required:"",autocomplete:"bank_ifsc"},null,8,["modelValue"]),a(i,{class:"mt-2",message:s(e).errors.bank_ifsc},null,8,["message"])]),o("div",K,[a(d,{for:"bank_account_no",value:"Bank Account No"}),a(m,{id:"bank_account_no",type:"text",class:"block w-full mt-1",modelValue:s(e).bank_account_no,"onUpdate:modelValue":l[12]||(l[12]=t=>s(e).bank_account_no=t),required:"",autocomplete:"bank_account_no"},null,8,["modelValue"]),a(i,{class:"mt-2",message:s(e).errors.bank_account_no},null,8,["message"])]),o("div",M,[a(d,{for:"adcode",value:"ADCode"}),a(m,{id:"adcode",type:"text",class:"block w-full mt-1",modelValue:s(e).adcode,"onUpdate:modelValue":l[13]||(l[13]=t=>s(e).adcode=t),required:"",autocomplete:"adcode"},null,8,["modelValue"]),a(i,{class:"mt-2",message:s(e).errors.adcode},null,8,["message"])]),o("div",j,[a(d,{for:"formFile",value:"Company Logo"}),o("input",{class:"form-control",id:"formFile",type:"file",onInput:l[14]||(l[14]=t=>s(e).logo=t.target.files[0])},null,32),a(i,{class:"mt-2",message:s(e).errors.logo},null,8,["message"]),n.editdata?(r(),c("img",{key:0,src:s(e).logo,alt:"",sizes:"",srcset:""},null,8,G)):u("",!0)]),o("div",L,[a(d,{for:"sign",value:"Signature"}),o("input",{class:"form-control",id:"sign",type:"file",onInput:l[15]||(l[15]=t=>s(e).sign=t.target.files[0])},null,32),a(i,{class:"mt-2",message:s(e).errors.sign},null,8,["message"]),s(e).progress?(r(),c("progress",{key:0,value:s(e).progress.percentage,max:"100"},p(s(e).progress.percentage)+"% ",9,O)):u("",!0),n.editdata?(r(),c("img",{key:1,src:s(e).sign,alt:"",sizes:"",srcset:""},null,8,P)):u("",!0)])]),o("div",Q,[a(x,{disabled:s(e).processing},{default:_(()=>[V(p(n.editdata.company_name?"Update":"Save"),1)]),_:1},8,["disabled"]),a(v,{"enter-active-class":"transition ease-in-out","enter-from-class":"opacity-0","leave-active-class":"transition ease-in-out","leave-to-class":"opacity-0"},{default:_(()=>[s(e).recentlySuccessful?(r(),c("p",H," Saved. ")):u("",!0)]),_:1})])],40,q)]))}};export{Y as default};
