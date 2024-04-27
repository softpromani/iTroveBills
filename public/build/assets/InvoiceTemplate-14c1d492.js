import{q as f,r as y,o as t,c as i,a,u as w,b as o,w as N,t as r,F as _,s as T,l as g,Z as I,d as x}from"./app-409dadc9.js";const E=o("i",{class:"fa fa-print","aria-hidden":"true"},null,-1),S=[E],D=o("i",{class:"fa fa-download","aria-hidden":"true"},null,-1),O=[D],A={class:"container-fluid downloadbill"},C={class:"row text-center text-center border-t border-r border-black border-solid"},F={class:"row"},M={class:"border-t border-b border-l border-black border-solid col-2"},W=["src"],P={class:"border-t border-b border-r border-black border-solid col-10"},B={class:"text-center text-7xl font"},L={class:"text-center"},V={class:"row"},$={class:"border-b border-l border-black border-solid col-4"},R={class:"text-center"},j={class:"border-b border-l border-black border-solid col-4"},q={class:"text-center"},G={class:"border-b border-l border-r border-black border-solid col-4"},H={class:"text-center"},U={class:"row"},Z={class:"border-b border-l border-r border-black border-solid col-12"},K={class:"text-center"},Q={class:"row"},z=o("div",{class:"font-bold border-b border-l border-black border-solid col-3"}," Invoice No: ",-1),J={class:"border-b border-l border-black border-solid col-3"},X=o("div",{class:"font-bold border-b border-l border-black border-solid col-3"}," DATE : ",-1),Y={class:"border-b border-l border-r border-black border-solid col-3"},oo=o("div",{class:"row"},[o("div",{class:"border-b border-l border-r border-black border-solid col-12"}," Details of reciever(Billed to): ")],-1),ro={class:"row"},eo=o("div",{class:"font-bold border-b border-l border-black border-solid col-2"}," NAME : ",-1),so={class:"border-b border-l border-black border-solid col-4"},lo=o("div",{class:"font-bold border-b border-l border-black border-solid col-2"}," MOBILE NO. : ",-1),co={class:"border-b border-l border-r border-black border-solid col-4"},bo={class:"row"},to=o("div",{class:"font-bold border-b border-l border-black border-solid col-2"}," ADDRESS : ",-1),io={class:"border-b border-l border-r border-black border-solid col-10"},no={class:"row"},ao=o("div",{class:"font-bold border-b border-l border-black border-solid col-3"}," ID/TPIN/TIN NO ",-1),_o={class:"border-b border-l border-black border-solid col-3"},vo=o("div",{class:"font-bold border-b border-l border-black border-solid col-3"}," VEHICLE NO ",-1),ho={class:"border-b border-l border-r border-black border-solid col-3"},ko=g('<div class="row"><div class="font-bold border-b border-l border-black border-solid col-1"> S.No </div><div class="font-bold border-b border-l border-black border-solid col-2"> Description of Goods </div><div class="font-bold border-b border-l border-black border-solid col-2"> HSN CODE </div><div class="font-bold border-b border-l border-r border-black border-solid col-2"> Quantity </div><div class="font-bold border-b border-black border-solid col-1"> Unit </div><div class="font-bold border-b border-l border-black border-solid col-1"> Weight </div><div class="font-bold border-b border-l border-black border-solid col-1"> Rate </div><div class="font-bold border-b border-l border-r border-black border-solid col-2"> Taxable Value </div></div>',1),po={class:"border-b border-l border-black border-solid col-1"},uo={class:"border-b border-l border-black border-solid col-2"},mo={class:"border-b border-l border-black border-solid col-2"},fo={class:"border-b border-l border-r border-black border-solid col-2"},yo={class:"border-b border-black border-solid col-1"},wo={class:"border-b border-l border-black border-solid col-1"},No={class:"border-b border-l border-black border-solid col-1"},To={class:"border-b border-l border-r border-black border-solid col-2"},go={class:"row"},Io=o("div",{class:"font-bold border-b border-l border-black border-solid col-3"}," No. OF PACKAGES ",-1),xo={class:"border-b border-l border-black border-solid col-1"},Eo=o("div",{class:"font-bold border-b border-l border-black border-solid col-3"}," Total weight ",-1),So={class:"border-b border-l border-black border-solid col-1"},Do=o("div",{class:"font-bold border-b border-l border-black border-solid col-2"}," Sub Total ",-1),Oo={class:"border-b border-l border-r border-black border-solid col-2"},Ao={class:"row"},Co=o("div",{class:"font-bold border-b border-l border-black border-solid col-4"}," Amount in words ",-1),Fo={class:"border-b border-l border-r border-black border-solid col-8"},Mo={class:"row"},Wo=o("div",{class:"font-bold border-b border-l border-black border-solid col-6"}," OUR BANK DETAILS ",-1),Po={class:"font-bold border-b border-l border-r border-black border-solid col-6"},Bo={class:"row"},Lo={class:"font-bold border-b border-l border-black border-solid col-6"},Vo={class:"pt-3"},$o={class:"-pt-3"},Ro={class:"-pt-3"},jo={class:"-pt-3"},qo={class:"font-bold border-b border-l border-r border-black border-solid col-6"},Go=["src"],Uo={__name:"InvoiceTemplate",props:{invoice:Object},setup(v){const h=()=>{window.print()},e=v,n=["Zero","One","Two","Three","Four","Five","Six","Seven","Eight","Nine"],k=["Ten","Twenty","Thirty","Forty","Fifty","Sixty","Seventy","Eighty","Ninety"],p=["Eleven","Twelve","Thirteen","Fourteen","Fifteen","Sixteen","Seventeen","Eighteen","Nineteen"],b=d=>{if(d===0)return"";if(d<10)return n[d];if(d<20)return p[d-10];if(d<100)return`${k[Math.floor(d/10)]} ${n[d%10]}`;{const l=Math.floor(Math.log10(d)/3),c=[""," Hundred"," Thousand"," Lakh"," Crore"][l],s=d%10**l;return`${b(Math.floor(d/10**l))} ${c} ${b(s)}`}},u=()=>{const d=Math.floor(e.invoice.total_ammount),l=Math.round((e.invoice.total_ammount-d)*100),c=b(l);d===0?amountInWords.value="Zero Rupees":amountInWords.value=`${b(d)} Rupees and ${c} Paise`};return f(()=>{u()}),(d,l)=>{const c=y("center");return t(),i(_,null,[a(w(I),{title:"Customer Bill"}),o("button",{onClick:h,class:"m-3 btn btn-outline-dark buttondata"},S),o("button",{onClick:l[0]||(l[0]=(...s)=>d.exportToPDF&&d.exportToPDF(...s)),class:"m-3 btn btn-outline-dark buttondata"},O),o("div",A,[o("div",C,[a(c,null,{default:N(()=>[x("Export Invoice")]),_:1})]),o("div",F,[o("div",M,[o("img",{class:"img",src:e.invoice.company.logo??"",alt:"jpeg"},null,8,W)]),o("div",P,[o("h1",B,r(e.invoice.company.company_name??""),1),o("p",L,r(e.invoice.company.address??""),1)])]),o("div",V,[o("div",$,[o("p",R," GSTIN- "+r(e.invoice.company.gstin??""),1)]),o("div",j,[o("p",q," LUT NO- "+r(e.invoice.company.company_lut[0].lut_no??""),1)]),o("div",G,[o("p",H," IEC-"+r(e.invoice.company.iec??""),1)])]),o("div",U,[o("div",Z,[o("p",K," MOBILE-"+r(e.invoice.company.mobile??"")+"     EMAIL-"+r(e.invoice.company.email??""),1)])]),o("div",Q,[z,o("div",J,r(e.invoice.invoice_number??""),1),X,o("div",Y,r(e.invoice.invoice_date??""),1)]),oo,o("div",ro,[eo,o("div",so,r(e.invoice.customer.company_name??""),1),lo,o("div",co,r(e.invoice.customer.mobile??""),1)]),o("div",bo,[to,o("div",io,r(e.invoice.customer.address??"No address"),1)]),o("div",no,[ao,o("div",_o,r(e.invoice.customer.gstin??"No ID/TPIN/TIN NO"),1),vo,o("div",ho,r(e.invoice.vehicle_no??""),1)]),ko,(t(!0),i(_,null,T(e.invoice.invoiceitems,(s,m)=>(t(),i("div",{class:"row",key:s.id},[o("div",po,r(m+1),1),o("div",uo,r(s.desc_product??""),1),o("div",mo,r(s.hsn_code??""),1),o("div",fo,r(s.quantity??""),1),o("div",yo,r(s.unit??""),1),o("div",wo,r(s.weight??""),1),o("div",No,r(s.rate??""),1),o("div",To,r(s.rate*s.quantity),1)]))),128)),o("div",go,[Io,o("div",xo,r(e.invoice.no_packets??"No Packs"),1),Eo,o("div",So,r(e.invoice.total_weight??""),1),Do,o("div",Oo," ₹ "+r(e.invoice.total_ammount??""),1)]),o("div",Ao,[Co,o("div",Fo,r(d.amountInWords),1)]),o("div",Mo,[Wo,o("div",Po,r(e.invoice.company.company_name??""),1)]),o("div",Bo,[o("div",Lo,[o("p",Vo," Name of bank - "+r(e.invoice.company.bank_name??""),1),o("p",$o," IFSC-"+r(e.invoice.company.bank_ifsc??""),1),o("p",Ro," Account Number - "+r(e.invoice.company.bank_account_no??""),1),o("p",jo," A D CODE-"+r(e.invoice.company.ad_code??""),1)]),o("div",qo,[o("img",{class:"img",src:e.invoice.company.sign??"",alt:"jpeg",style:{height:"80px",width:"230px"}},null,8,Go)])])])],64)}}};export{Uo as default};
