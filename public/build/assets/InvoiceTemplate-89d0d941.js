import{m as y,q as w,r as N,o as n,c as a,a as _,u as T,b as o,w as g,t as r,F as v,s as x,l as I,Z as E,d as S}from"./app-b41668dc.js";const D=o("i",{class:"fa fa-print","aria-hidden":"true"},null,-1),O=[D],C=o("i",{class:"fa fa-download","aria-hidden":"true"},null,-1),F=[C],M={class:"container-fluid downloadbill"},A={class:"row text-center text-center border-t border-r border-black border-solid"},P={class:"row"},B={class:"border-t border-b border-l border-black border-solid col-2"},L=["src"],W={class:"border-t border-b border-r border-black border-solid col-10"},V={class:"text-center text-7xl font"},R={class:"text-center"},j={class:"row"},q={class:"border-b border-l border-black border-solid col-4"},G={class:"text-center"},H={class:"border-b border-l border-black border-solid col-4"},U={class:"text-center"},K={class:"border-b border-l border-r border-black border-solid col-4"},Z={class:"text-center"},$={class:"row"},Q={class:"border-b border-l border-r border-black border-solid col-12"},z={class:"text-center"},J={class:"row"},X=o("div",{class:"font-bold border-b border-l border-black border-solid col-3"}," Invoice No: ",-1),Y={class:"border-b border-l border-black border-solid col-3"},oo=o("div",{class:"font-bold border-b border-l border-black border-solid col-3"}," DATE : ",-1),ro={class:"border-b border-l border-r border-black border-solid col-3"},eo=o("div",{class:"row"},[o("div",{class:"border-b border-l border-r border-black border-solid col-12"}," Details of reciever(Billed to): ")],-1),so={class:"row"},lo=o("div",{class:"font-bold border-b border-l border-black border-solid col-2"}," NAME : ",-1),co={class:"border-b border-l border-black border-solid col-4"},bo=o("div",{class:"font-bold border-b border-l border-black border-solid col-2"}," MOBILE NO. : ",-1),io={class:"border-b border-l border-r border-black border-solid col-4"},to={class:"row"},no=o("div",{class:"font-bold border-b border-l border-black border-solid col-2"}," ADDRESS : ",-1),ao={class:"border-b border-l border-r border-black border-solid col-10"},_o={class:"row"},vo=o("div",{class:"font-bold border-b border-l border-black border-solid col-3"}," ID/TPIN/TIN NO ",-1),ho={class:"border-b border-l border-black border-solid col-3"},ko=o("div",{class:"font-bold border-b border-l border-black border-solid col-3"}," VEHICLE NO ",-1),po={class:"border-b border-l border-r border-black border-solid col-3"},fo=I('<div class="row"><div class="font-bold border-b border-l border-black border-solid col-1"> S.No </div><div class="font-bold border-b border-l border-black border-solid col-2"> Description of Goods </div><div class="font-bold border-b border-l border-black border-solid col-2"> HSN CODE </div><div class="font-bold border-b border-l border-r border-black border-solid col-2"> Quantity </div><div class="font-bold border-b border-black border-solid col-1"> Unit </div><div class="font-bold border-b border-l border-black border-solid col-1"> Weight </div><div class="font-bold border-b border-l border-black border-solid col-1"> Rate </div><div class="font-bold border-b border-l border-r border-black border-solid col-2"> Taxable Value </div></div>',1),uo={class:"border-b border-l border-black border-solid col-1"},mo={class:"border-b border-l border-black border-solid col-2"},yo={class:"border-b border-l border-black border-solid col-2"},wo={class:"border-b border-l border-r border-black border-solid col-2"},No={class:"border-b border-black border-solid col-1"},To={class:"border-b border-l border-black border-solid col-1"},go={class:"border-b border-l border-black border-solid col-1"},xo={class:"border-b border-l border-r border-black border-solid col-2"},Io={class:"row"},Eo=o("div",{class:"font-bold border-b border-l border-black border-solid col-3"}," No. OF PACKAGES ",-1),So={class:"border-b border-l border-black border-solid col-1"},Do=o("div",{class:"font-bold border-b border-l border-black border-solid col-3"}," Total weight ",-1),Oo={class:"border-b border-l border-black border-solid col-1"},Co=o("div",{class:"font-bold border-b border-l border-black border-solid col-2"}," Sub Total ",-1),Fo={class:"border-b border-l border-r border-black border-solid col-2"},Mo={class:"row"},Ao=o("div",{class:"font-bold border-b border-l border-black border-solid col-4"}," Amount in words ",-1),Po={class:"border-b border-l border-r border-black border-solid col-8"},Bo={class:"row"},Lo=o("div",{class:"font-bold border-b border-l border-black border-solid col-6"}," OUR BANK DETAILS ",-1),Wo={class:"font-bold border-b border-l border-r border-black border-solid col-6"},Vo={class:"row"},Ro={class:"font-bold border-b border-l border-black border-solid col-6"},jo={class:"pt-3"},qo={class:"-pt-3"},Go={class:"-pt-3"},Ho={class:"-pt-3"},Uo={class:"font-bold border-b border-l border-r border-black border-solid col-6"},Ko=["src"],$o={__name:"InvoiceTemplate",props:{invoice:Object},setup(h){const k=()=>{window.print()},e=h,c=y(""),i=["","One","Two","Three","Four","Five","Six","Seven","Eight","Nine"],p=["","Ten","Twenty","Thirty","Forty","Fifty","Sixty","Seventy","Eighty","Ninety"],f=["Ten","Eleven","Twelve","Thirteen","Fourteen","Fifteen","Sixteen","Seventeen","Eighteen","Nineteen"],l=d=>(console.log(d),d<1?"":d<10?i[d]:d<20?f[d-10]:d<100?p[Math.floor(d/10)]+" "+i[d%10]:d<1e3?i[Math.floor(d/100)]+" Hundred "+l(d%100):d<1e5?l(Math.floor(d/1e3))+" Thousand "+l(d%1e3):d<1e7?l(Math.floor(d/1e5))+" Lakh "+l(d%1e5):l(Math.floor(d/1e7))+" Crore "+l(d%1e7)),u=()=>{const d=Math.floor(e.invoice.total_ammount),b=Math.round((e.invoice.total_ammount-d)*100),t=l(b);d===0?c.value="Zero Rupees":(console.log(c.value),c.value=`${l(d)} Rupees and ${t} Paise`)};return w(()=>{u()}),(d,b)=>{const t=N("center");return n(),a(v,null,[_(T(E),{title:"Customer Bill"}),o("button",{onClick:k,class:"m-3 btn btn-outline-dark buttondata"},O),o("button",{onClick:b[0]||(b[0]=(...s)=>d.exportToPDF&&d.exportToPDF(...s)),class:"m-3 btn btn-outline-dark buttondata"},F),o("div",M,[o("div",A,[_(t,null,{default:g(()=>[S("Export Invoice")]),_:1})]),o("div",P,[o("div",B,[o("img",{class:"img",src:e.invoice.company.logo??"",alt:"jpeg"},null,8,L)]),o("div",W,[o("h1",V,r(e.invoice.company.company_name??""),1),o("p",R,r(e.invoice.company.address??""),1)])]),o("div",j,[o("div",q,[o("p",G," GSTIN- "+r(e.invoice.company.gstin??""),1)]),o("div",H,[o("p",U," LUT NO- "+r(e.invoice.company.company_lut[0].lut_no??""),1)]),o("div",K,[o("p",Z," IEC-"+r(e.invoice.company.iec??""),1)])]),o("div",$,[o("div",Q,[o("p",z," MOBILE-"+r(e.invoice.company.mobile??"")+"     EMAIL-"+r(e.invoice.company.email??""),1)])]),o("div",J,[X,o("div",Y,r(e.invoice.invoice_number??""),1),oo,o("div",ro,r(e.invoice.invoice_date??""),1)]),eo,o("div",so,[lo,o("div",co,r(e.invoice.customer.company_name??""),1),bo,o("div",io,r(e.invoice.customer.mobile??""),1)]),o("div",to,[no,o("div",ao,r(e.invoice.customer.address??"No address"),1)]),o("div",_o,[vo,o("div",ho,r(e.invoice.customer.gstin??"No ID/TPIN/TIN NO"),1),ko,o("div",po,r(e.invoice.vehicle_no??""),1)]),fo,(n(!0),a(v,null,x(e.invoice.invoiceitems,(s,m)=>(n(),a("div",{class:"row",key:s.id},[o("div",uo,r(m+1),1),o("div",mo,r(s.desc_product??""),1),o("div",yo,r(s.hsn_code??""),1),o("div",wo,r(s.quantity??""),1),o("div",No,r(s.unit??""),1),o("div",To,r(s.weight??""),1),o("div",go,r(s.rate??""),1),o("div",xo,r(s.rate*s.quantity),1)]))),128)),o("div",Io,[Eo,o("div",So,r(e.invoice.no_packets??"No Packs"),1),Do,o("div",Oo,r(e.invoice.total_weight??""),1),Co,o("div",Fo," ₹ "+r(e.invoice.total_ammount??""),1)]),o("div",Mo,[Ao,o("div",Po,r(c.value),1)]),o("div",Bo,[Lo,o("div",Wo,r(e.invoice.company.company_name??""),1)]),o("div",Vo,[o("div",Ro,[o("p",jo," Name of bank - "+r(e.invoice.company.bank_name??""),1),o("p",qo," IFSC-"+r(e.invoice.company.bank_ifsc??""),1),o("p",Go," Account Number - "+r(e.invoice.company.bank_account_no??""),1),o("p",Ho," A D CODE-"+r(e.invoice.company.ad_code??""),1)]),o("div",Uo,[o("img",{class:"img",src:e.invoice.company.sign??"",alt:"jpeg",style:{height:"80px",width:"230px"}},null,8,Ko)])])])],64)}}};export{$o as default};