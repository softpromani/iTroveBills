import{T as Z,p as s,o as n,c as i,a as d,u as _,w as h,F as E,Z as $,b as e,x as q,t as a,n as j,i as C,d as r,e as B,h as c,k as f,f as P,y as R,A as V}from"./app-edfd4d7d.js";import{_ as z}from"./AuthenticatedLayout-7f7d53a2.js";/* empty css                                              */import{S as v}from"./sweetalert2.all-0c96accb.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./FlashMessages-d23d8a3f.js";const L=e("div",{class:"inline-flex w-full mb-4 overflow-hidden bg-white rounded-lg shadow-md"},[e("div",{class:"flex items-center justify-center w-12 bg-pink-800"},[e("svg",{class:"w-6 h-6 text-white fill-current",viewBox:"0 0 40 40",xmlns:"http://www.w3.org/2000/svg"},[e("path",{d:"M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z"})])]),e("div",{class:"px-4 py-2 -mx-3"},[e("div",{class:"mx-3"},[e("span",{class:"font-semibold text-blue-500"},"Invoice List")])])],-1),G={class:"bg-white rounded-lg shadow"},W={class:"rounded-lg table-responsive"},J={class:"table",style:{"min-height":"200px"}},Q=e("thead",{class:"table-dark"},[e("tr",null,[e("th",{class:"col-1"},"Sr.no."),e("th",{class:"col-1"},"Invoice Number"),e("th",{class:"col-1"},"Invoice Date"),e("th",{class:"col-1"},"Total Amount"),e("th",{class:"col-1"},"Paid Amount"),e("th",{class:"col-2"},"Payment Status"),e("th",{class:"col-1"},"Vehicle No"),e("th",{class:"col-1"},"No. Packets"),e("th",{class:"col-1"},"Customer"),e("th",{class:"col-1"},"Company"),e("th",{class:"col-1"},"Action")])],-1),X={class:"dropdown"},Y=e("span",{class:"p-2",style:{"font-size":"15px",cursor:"pointer"},"data-bs-toggle":"dropdown","aria-expanded":"false"},[e("i",{class:"fa fa-ellipsis-v"})],-1),ee={class:"dropdown-menu"},te=e("i",{class:"fa-solid fa-pen-to-square",style:{color:"blueviolet"}},null,-1),oe=e("i",{class:"fa fa-print","aria-hidden":"true",style:{color:"rgb(241, 12, 12)"}},null,-1),le=e("i",{class:"fa fa-envelope-square","aria-hidden":"true",style:{color:"rgb(245, 180, 0)"}},null,-1),se=e("i",{class:"fa fa-envelope-square","aria-hidden":"true",style:{color:"rgb(245, 180, 0)"}},null,-1),ae=["onClick"],ne=e("i",{class:"fa fa-car","aria-hidden":"true",style:{color:"rgb(245, 180, 0)"}},null,-1),ie=["onClick"],de=e("i",{class:"fa fa-inr","aria-hidden":"true",style:{color:"rgb(245, 180, 0)"}},null,-1),re=e("div",{class:"flex flex-col items-center px-1 py-1 bg-white border-t xs:flex-row xs:justify-between"},null,-1),ce={key:0,class:"modal fade show",style:{display:"block"},"aria-modal":"true",role:"dialog"},ue={class:"modal-dialog",role:"document"},me={class:"modal-content"},pe=e("h5",{class:"modal-title"},"Update Package",-1),_e=e("span",null,"×",-1),he=[_e],fe={class:"modal-body"},ve={class:"form-group mb-3"},be=e("label",{for:"no_packets"},"No. of Package",-1),ye={class:"form-group mb-3"},ge=e("label",{for:"vehicle_no"},"Vehicle Number",-1),ke=e("button",{type:"submit",class:"btn btn-primary"},"Save changes",-1),xe={key:1,class:"modal-backdrop fade show"},we={key:2,class:"modal fade show",style:{display:"block"},"aria-modal":"true",role:"dialog"},Ce={class:"modal-dialog",role:"document"},Be={class:"modal-content"},Pe=e("h5",{class:"modal-title"},"Pay Bill",-1),Ve=e("span",null,"×",-1),Te=[Ve],Me={class:"modal-body"},Ne={class:"mb-3"},Se=e("legend",{class:"col-form-label pt-0"},"Payment Method",-1),Ee=["id","value"],Ue=["for"],qe={class:"form-group mb-3"},Oe=e("label",{for:"reference_no"},"Reference Number",-1),Ae={class:"form-group mb-3"},Ke=e("label",{for:"reference_no"},"Amount",-1),Ie={class:"form-group mb-3"},De=e("label",{for:"remark"},"Remark (if any)",-1),Fe=e("button",{type:"submit",class:"btn btn-primary"},"Submit",-1),He={key:3,class:"modal-backdrop fade show"},Ge={__name:"invoice_list",props:{invoices:Object},setup(O){const A=O;Z({});const b=s(!1),u=s(""),m=s(""),p=s(null),K=s(A.invoices),I=o=>{b.value=!0,p.value=o,V.get(`/api/fetch-invoice/${o}`).then(l=>{const t=l.data;u.value=t.no_packets||"",m.value=t.vehicle_no||"",p.value=t.id||""}).catch(l=>{console.error("Error fetching invoice:",l)})},T=()=>{b.value=!1,u.value="",m.value=""},D=()=>{V.post("/api/update-invoice-package",{no_packets:u.value,vehicle_no:m.value,invoice_id:p.value}).then(o=>{console.log("Package updated successfully:",o.data),v.fire({title:"Success!",text:"Package updated successfully.",icon:"success",confirmButtonText:"OK"}).then(()=>{setTimeout(()=>{location.reload()},1e4)}),T(),location.reload()}).catch(o=>{console.error("Error updating package:",o),v.fire({title:"Error!",text:"There was an error updating the package.",icon:"error",confirmButtonText:"OK"})})},y=s(!1),g=s(""),k=s(""),x=s(""),w=s(""),U=s([]),M=()=>{y.value=!1,k.value="",g.value="",x.value="",w.value=""},F=o=>{y.value=!0,p.value=o,V.get("/api/fetch-payment-types").then(l=>{U.value=l.data}).catch(l=>{console.error("Error fetching types:",l)})},H=()=>{V.post("/api/pay-bill",{payment_method_id:k.value,reference_no:g.value,amount:x.value,remark:w.value,invoice_id:p.value}).then(o=>{console.log("Bill paid successfully:",o),o.data.status==1?v.fire({title:"Success!",text:o.data.message,icon:"success",confirmButtonText:"OK"}).then(()=>{setTimeout(()=>{location.reload()},5e3)}):v.fire({title:"Info!",text:o.data.message,icon:"warning",confirmButtonText:"OK"}).then(()=>{setTimeout(()=>{location.reload()},5e3)}),M()}).catch(o=>{console.error("Error paying bill:",o),v.fire({title:"Error!",text:"There was an error paying the bill.",icon:"error",confirmButtonText:"OK"})})};return(o,l)=>(n(),i(E,null,[d(_($),{title:"Customer view"}),d(z,null,{default:h(()=>[L,e("div",G,[e("div",W,[e("table",J,[Q,e("tbody",null,[(n(!0),i(E,null,q(K.value,(t,N)=>(n(),i("tr",{key:t.id},[e("td",null,a(N+1),1),e("td",null,a(t.invoice_number??""),1),e("td",null,a(t.invoice_date??""),1),e("td",null,a(t.total_ammount??""),1),e("td",null,a(t.payment.paid_amount??""),1),e("td",null,[e("span",{class:j(["px-3 py-1 mr-2 text-xs font-medium rounded-full",{"bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300":t.payment.status==="due","bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300":t.payment.status==="partial-paid","bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300":t.payment.status==="paid"}])},a(t.payment.status),3)]),e("td",null,a(t.vehicle_no??""),1),e("td",null,a(t.no_packets??"NO PACK"),1),e("td",null,a(t.customer.company_name??""),1),e("td",null,a(t.company.company_name??""),1),e("td",null,[e("div",X,[Y,e("ul",ee,[e("li",null,[d(_(C),{class:"dropdown-item",href:o.route("customer.bill.edit"),method:"get",data:{invoice_id:t.id}},{default:h(()=>[te,r(" Edit")]),_:2},1032,["href","data"])]),e("li",null,[d(_(C),{href:o.route("view.invoice"),method:"get",data:{invoice_id:t.id},class:"dropdown-item"},{default:h(()=>[oe,r(" Print")]),_:2},1032,["href","data"])]),e("li",null,[d(_(C),{class:"dropdown-item",href:o.route("bill.sendmail"),method:"post",data:{invoice_id:t.id}},{default:h(()=>[le,r(" Mail")]),_:2},1032,["href","data"])]),e("li",null,[d(_(C),{class:"dropdown-item",href:o.route("create.eway.bill"),method:"get",data:{invoice_id:t.id}},{default:h(()=>[se,r(" Generate E-Way Bill")]),_:2},1032,["href","data"])]),e("li",null,[e("a",{onClick:B(S=>I(t.id),["prevent"]),class:"dropdown-item",href:"#"},[ne,r(" Package Update ")],8,ae)]),e("li",null,[e("a",{onClick:B(S=>F(t.id),["prevent"]),class:"dropdown-item",href:"#"},[de,r(" Pay Bill ")],8,ie)])])])])]))),128))])])]),re]),b.value?(n(),i("div",ce,[e("div",ue,[e("div",me,[e("div",{class:"modal-header"},[pe,e("button",{type:"button",class:"close",onClick:T},he)]),e("div",fe,[e("form",{onSubmit:B(D,["prevent"])},[e("div",ve,[be,c(e("input",{type:"number",class:"form-control",id:"no_packets","onUpdate:modelValue":l[0]||(l[0]=t=>u.value=t),required:""},null,512),[[f,u.value]])]),e("div",ye,[ge,c(e("input",{type:"text",class:"form-control",id:"vehicle_no","onUpdate:modelValue":l[1]||(l[1]=t=>m.value=t),required:""},null,512),[[f,m.value]])]),e("div",{class:"modal-footer"},[e("button",{type:"button",class:"btn btn-secondary",onClick:T},"Close"),ke])],32)])])])])):P("",!0),b.value?(n(),i("div",xe)):P("",!0),y.value?(n(),i("div",we,[e("div",Ce,[e("div",Be,[e("div",{class:"modal-header"},[Pe,e("button",{type:"button",class:"close",onClick:M},Te)]),e("div",Me,[e("form",{onSubmit:B(H,["prevent"])},[e("fieldset",Ne,[Se,(n(!0),i(E,null,q(U.value,(t,N)=>(n(),i("div",{key:N,class:"form-check form-check-inline"},[c(e("input",{class:"form-check-input",type:"radio",id:t.name,value:t.id,"onUpdate:modelValue":l[2]||(l[2]=S=>k.value=S)},null,8,Ee),[[R,k.value]]),e("label",{class:"form-check-label",for:t.name},a(t.name),9,Ue)]))),128))]),e("div",qe,[Oe,c(e("input",{type:"text",class:"form-control",id:"reference_no","onUpdate:modelValue":l[3]||(l[3]=t=>g.value=t),required:""},null,512),[[f,g.value]])]),e("div",Ae,[Ke,c(e("input",{type:"number",class:"form-control",id:"reference_no","onUpdate:modelValue":l[4]||(l[4]=t=>x.value=t),required:""},null,512),[[f,x.value]])]),e("div",Ie,[De,c(e("textarea",{class:"form-control",id:"remark","onUpdate:modelValue":l[5]||(l[5]=t=>w.value=t)},null,512),[[f,w.value]])]),e("div",{class:"modal-footer"},[e("button",{type:"button",class:"btn btn-secondary",onClick:M},"Close"),Fe])],32)])])])])):P("",!0),y.value?(n(),i("div",He)):P("",!0)]),_:1})],64))}};export{Ge as default};
