const master={
  channels:['Suggestion Box','WhatsApp / Hotline','Direct (Face to Face)','Worker Representative'],
  messageTypes:['Ask','Suggestion','Report'],
  caseTypes:['Occupational Health, Safety & Environment','Wages & Incentives','Benefits','General Facilities','Harassment & Abuse','Working Hours','Production','Recruitment & Contract','Personal Change & Performance Appraisal','Disciplinary Action','Workplace Disputes','Communication & Grievance Channels','Freedom of association and workers representation','Personal Affairs','Others','Junks'],
  departments:['Production','HRGA','Maintenance','Quality','Logistics','General Affairs','Compliance'],
  pics:['Rika Amelia','Dedi Kurniawan','Iwan Setiawan','Siti Nurhayati','Budi Santoso','Rina Handayani','Maya Putri']
};
const sampleCases=[
{id:'GRV-2026-0712',site:'Cijerah',dateReceived:'2026-07-28',dateResponse:'2026-07-28',dateClosed:'',channel:'Suggestion Box',messageType:'Report',message:'Ventilasi area produksi kurang baik sehingga ruangan terasa panas dan pengap, terutama pada siang hari.',managementResponse:'Terima kasih atas informasinya. Tim Production dan Maintenance akan melakukan pemeriksaan kondisi ventilasi.',department:'Production',caseType:'Working Conditions',rootCause:'Sirkulasi udara tidak optimal karena exhaust fan tidak berfungsi maksimal dan penataan mesin menghambat aliran udara.',correctiveAction:'Perbaikan exhaust fan dan penataan ulang mesin untuk memperbaiki aliran udara.',status:'In Progress',repeated:'No',satisfaction:'No Rating',rating:0,pic:'Rika Amelia',priority:'Medium',confidential:'No',targetResponse:'2026-07-29',targetClosure:'2026-08-03',responseHours:2.1,closureDays:null,evidence:2},
{id:'GRV-2026-0711',site:'Majalaya',dateReceived:'2026-07-27',dateResponse:'',dateClosed:'',channel:'WhatsApp',messageType:'Report',message:'Karyawan meminta klarifikasi terkait perhitungan insentif bulan berjalan.',managementResponse:'',department:'HRGA',caseType:'Compensation & Benefit',rootCause:'',correctiveAction:'',status:'Open',repeated:'No',satisfaction:'No Rating',rating:0,pic:'Dedi Kurniawan',priority:'High',confidential:'No',targetResponse:'2026-07-28',targetClosure:'2026-07-31',responseHours:null,closureDays:null,evidence:0},
{id:'GRV-2026-0710',site:'Cijerah',dateReceived:'2026-07-25',dateResponse:'2026-07-25',dateClosed:'',channel:'Direct (Face to Face)',messageType:'Report',message:'Assembly point terhalang oleh parkir sepeda pada jam masuk kerja.',managementResponse:'Tim General Affairs telah diminta mengosongkan area assembly point.',department:'General Affairs',caseType:'Safety & Health',rootCause:'Pengaturan parkir belum menetapkan batas steril assembly point.',correctiveAction:'Pemasangan garis batas, papan larangan parkir, dan pengawasan rutin.',status:'Overdue',repeated:'Yes',satisfaction:'No Rating',rating:0,pic:'Rina Handayani',priority:'High',confidential:'No',targetResponse:'2026-07-26',targetClosure:'2026-07-29',responseHours:4.2,closureDays:null,evidence:1},
{id:'GRV-2026-0709',site:'Majalaya',dateReceived:'2026-07-23',dateResponse:'2026-07-23',dateClosed:'2026-07-27',channel:'Email',messageType:'Suggestion',message:'Usulan penambahan dispenser air minum di area packing.',managementResponse:'Usulan diterima dan telah dikoordinasikan dengan General Affairs.',department:'General Affairs',caseType:'Facilities',rootCause:'Jarak area packing ke titik air minum terdekat cukup jauh.',correctiveAction:'Menambah satu dispenser di area packing.',status:'Closed',repeated:'No',satisfaction:'Yes',rating:5,pic:'Rina Handayani',priority:'Low',confidential:'No',targetResponse:'2026-07-24',targetClosure:'2026-07-30',responseHours:1.0,closureDays:4,evidence:2},
{id:'GRV-2026-0708',site:'Cijerah',dateReceived:'2026-07-21',dateResponse:'2026-07-22',dateClosed:'',channel:'Suggestion Box',messageType:'Report',message:'Masker kerja tidak selalu tersedia di awal shift.',managementResponse:'Stock masker sedang diverifikasi dan distribusi harian akan diperbaiki.',department:'HRGA',caseType:'Safety & Health',rootCause:'Kontrol stok dan jadwal distribusi tidak terdokumentasi dengan baik.',correctiveAction:'Membuat minimum stock dan checklist distribusi APD.',status:'In Progress',repeated:'Yes',satisfaction:'No Rating',rating:0,pic:'Maya Putri',priority:'High',confidential:'No',targetResponse:'2026-07-22',targetClosure:'2026-08-01',responseHours:18.0,closureDays:null,evidence:1},
{id:'GRV-2026-0707',site:'Majalaya',dateReceived:'2026-07-18',dateResponse:'2026-07-18',dateClosed:'2026-07-22',channel:'Hotline',messageType:'Ask',message:'Pertanyaan mengenai prosedur pengajuan izin keluarga.',managementResponse:'Prosedur dan formulir izin telah dijelaskan kepada pekerja.',department:'HRGA',caseType:'Workplace Relations',rootCause:'Informasi prosedur belum tersosialisasi secara merata.',correctiveAction:'Refresh sosialisasi izin kepada seluruh kepala regu.',status:'Closed',repeated:'No',satisfaction:'Yes',rating:4,pic:'Dedi Kurniawan',priority:'Low',confidential:'No',targetResponse:'2026-07-19',targetClosure:'2026-07-24',responseHours:0.8,closureDays:4,evidence:0},
{id:'GRV-2026-0606',site:'Cijerah',dateReceived:'2026-06-24',dateResponse:'2026-06-24',dateClosed:'2026-06-29',channel:'Worker Representative',messageType:'Report',message:'Perbedaan komunikasi target produksi antara dua shift.',managementResponse:'Supervisor kedua shift diminta melakukan penyamaan briefing.',department:'Production',caseType:'Workplace Relations',rootCause:'Tidak ada briefing standard antarsupervisor.',correctiveAction:'Membuat format briefing shift dan serah terima target.',status:'Closed',repeated:'No',satisfaction:'Yes',rating:4,pic:'Rika Amelia',priority:'Medium',confidential:'No',targetResponse:'2026-06-25',targetClosure:'2026-06-30',responseHours:3.4,closureDays:5,evidence:1},
{id:'GRV-2026-0605',site:'Majalaya',dateReceived:'2026-06-15',dateResponse:'2026-06-16',dateClosed:'2026-06-22',channel:'Suggestion Box',messageType:'Suggestion',message:'Penambahan rak penyimpanan barang pribadi di area produksi.',managementResponse:'Kebutuhan dan lokasi rak sedang dikaji.',department:'General Affairs',caseType:'Facilities',rootCause:'Kapasitas locker yang ada belum mencukupi.',correctiveAction:'Menambah rak sementara dan memasukkan locker dalam budget.',status:'Closed',repeated:'No',satisfaction:'Yes',rating:5,pic:'Budi Santoso',priority:'Low',confidential:'No',targetResponse:'2026-06-17',targetClosure:'2026-06-25',responseHours:20.5,closureDays:7,evidence:2},
{id:'GRV-2026-0504',site:'Cijerah',dateReceived:'2026-05-20',dateResponse:'2026-05-20',dateClosed:'2026-05-24',channel:'WhatsApp',messageType:'Report',message:'Lantai licin di dekat area pencucian.',managementResponse:'Area diamankan dan tim maintenance melakukan pengecekan.',department:'Maintenance',caseType:'Safety & Health',rootCause:'Drainase meluap saat debit air tinggi.',correctiveAction:'Membersihkan saluran dan memasang anti-slip mat.',status:'Closed',repeated:'No',satisfaction:'Yes',rating:5,pic:'Iwan Setiawan',priority:'High',confidential:'No',targetResponse:'2026-05-21',targetClosure:'2026-05-26',responseHours:0.5,closureDays:4,evidence:3},
{id:'GRV-2026-0403',site:'Majalaya',dateReceived:'2026-04-08',dateResponse:'2026-04-08',dateClosed:'2026-04-15',channel:'Direct (Face to Face)',messageType:'Report',message:'Pekerja merasa jadwal lembur disampaikan terlalu mendadak.',managementResponse:'Supervisor diminta menyampaikan rencana lembur lebih awal.',department:'Production',caseType:'Working Hours',rootCause:'Perencanaan produksi berubah tanpa waktu komunikasi minimum.',correctiveAction:'Menetapkan batas waktu pemberitahuan lembur dan konfirmasi sukarela.',status:'Closed',repeated:'Yes',satisfaction:'No',rating:2,pic:'Rika Amelia',priority:'Medium',confidential:'No',targetResponse:'2026-04-09',targetClosure:'2026-04-16',responseHours:2.0,closureDays:7,evidence:1},
{id:'GRV-2026-0302',site:'Cijerah',dateReceived:'2026-03-11',dateResponse:'2026-03-12',dateClosed:'2026-03-20',channel:'Hotline',messageType:'Report',message:'Pelapor menyampaikan masalah komunikasi yang tidak pantas dari atasan.',managementResponse:'Kasus diterima secara rahasia dan ditangani oleh tim HR.',department:'HRGA',caseType:'Harassment & Abuse',rootCause:'Standar komunikasi kepemimpinan belum dipahami konsisten.',correctiveAction:'Coaching, corrective action, dan refresh training respectful workplace.',status:'Closed',repeated:'No',satisfaction:'Yes',rating:4,pic:'Maya Putri',priority:'High',confidential:'Yes',targetResponse:'2026-03-12',targetClosure:'2026-03-22',responseHours:12,closureDays:9,evidence:2},
{id:'GRV-2026-0201',site:'Majalaya',dateReceived:'2026-02-06',dateResponse:'2026-02-06',dateClosed:'2026-02-10',channel:'Email',messageType:'Ask',message:'Permintaan penjelasan mengenai hak cuti tahunan.',managementResponse:'HR memberikan penjelasan berdasarkan masa kerja dan saldo cuti.',department:'HRGA',caseType:'Compensation & Benefit',rootCause:'Informasi saldo cuti belum mudah diakses pekerja.',correctiveAction:'Menambahkan informasi saldo cuti pada slip atau portal karyawan.',status:'Closed',repeated:'No',satisfaction:'Yes',rating:5,pic:'Dedi Kurniawan',priority:'Low',confidential:'No',targetResponse:'2026-02-07',targetClosure:'2026-02-12',responseHours:1.5,closureDays:4,evidence:0}
];
let cases=JSON.parse(localStorage.getItem('khx_grievance_cases')||'null')||sampleCases;
let currentCaseId=cases[0]?.id; let currentLang='ID';
const colors=['#176be6','#14a66f','#f4a11a','#7a5be0','#24a9b8','#e05259','#7e91aa','#3b83cf'];
const titles={dashboard:['Grievance Dashboard','Cijerah & Majalaya'], 'case-log':['Case Log','Manage and monitor all grievance cases'], 'new-case':['New Grievance Case','Create and assign a grievance case'], 'case-detail':['Case Detail / Follow Up','Complete case history and corrective action'], 'follow-up':['Follow Up Board','Monitor open and ongoing cases'],reports:['Reports','Management review and data exports'],'master-data':['Master Data','Manage dropdown values and classifications'],users:['User Management','Role-based system access'],settings:['Settings','System, privacy, and SLA configuration']};
function save(){localStorage.setItem('khx_grievance_cases',JSON.stringify(cases))}
function todayISO(){return new Date().toISOString().slice(0,10)}
function fmtDate(s){if(!s)return'-';return new Date(s+'T00:00:00').toLocaleDateString('en-GB',{day:'2-digit',month:'short',year:'numeric'})}
function cls(s){return s==='In Progress'?'progress':s.toLowerCase()}
function statusBadge(s){return `<span class="status ${cls(s)}">${s==='Overdue'?'⚠ ':''}${s}</span>`}
function priorityBadge(s){return `<span class="priority ${s.toLowerCase()}">${s}</span>`}
function toast(msg){const el=document.getElementById('toast');el.textContent=msg;el.classList.add('show');clearTimeout(window.toastT);window.toastT=setTimeout(()=>el.classList.remove('show'),2800)}
function showPage(name){document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));document.getElementById('page-'+name).classList.add('active');document.querySelectorAll('.nav-btn').forEach(b=>b.classList.toggle('active',b.dataset.page===name));document.getElementById('pageTitle').textContent=titles[name][0];document.getElementById('pageSubtitle').textContent=titles[name][1];document.getElementById('sidebar').classList.remove('open');window.scrollTo({top:0,behavior:'smooth'});if(name==='dashboard')renderDashboard();if(name==='case-log')renderCaseLog();if(name==='follow-up')renderFollowBoard();if(name==='master-data')renderMaster();if(name==='users')renderUsers();}
window.showPage=showPage;window.toast=toast;
function fillSelect(sel,arr,placeholder){const el=typeof sel==='string'?document.querySelector(sel):sel;if(!el)return;const existing=placeholder?`<option value="">${placeholder}</option>`:'';el.innerHTML=existing+arr.map(x=>`<option value="${x}">${x}</option>`).join('')}
function initSelects(){
  // Pake tanda tanya (?.) biar kalo elemennya gak ada, dia gak error (Optional Chaining)
  ['dashType','logType'].forEach(id => document.getElementById(id)?.insertAdjacentHTML('beforeend', master.caseTypes.map(x=>`<option>${x}</option>`).join('')));
  ['dashDept','logDept'].forEach(id => document.getElementById(id)?.insertAdjacentHTML('beforeend', master.departments.map(x=>`<option>${x}</option>`).join('')));
  
  // Cek apakah form new case sudah ada di HTML
  const f = document.getElementById('newCaseForm');
  if(f) {
    fillSelect(f.channel, master.channels, 'Select Channel');
    fillSelect(f.messageType, master.messageTypes, 'Select Message Type');
    fillSelect(f.caseType, master.caseTypes, 'Select Case Type');
    fillSelect(f.department, master.departments, 'Select Department');
    fillSelect(f.pic, master.pics, 'Select PIC');
    f.dateReceived.value = todayISO();
    const d = new Date(); d.setDate(d.getDate()+1); 
    f.targetResponse.value = d.toISOString().slice(0,10);
    d.setDate(d.getDate()+6); 
    f.targetClosure.value = d.toISOString().slice(0,10);
  }
}
function dashboardFiltered() {
    // Kita ambil nilainya dengan aman. Kalau elemen filternya gak ada di layar, default ke 'All'
    const site = document.getElementById('dashSite')?.value || 'All';
    const year = document.getElementById('dashYear')?.value || '2026'; // Default tahun
    const month = document.getElementById('dashMonth')?.value || 'All';
    const status = document.getElementById('dashStatus')?.value || 'All';
    const type = document.getElementById('dashType')?.value || 'All';
    const dept = document.getElementById('dashDept')?.value || 'All';

    return cases.filter(c => 
        (site === 'All' || c.site === site) &&
        c.dateReceived.startsWith(year) &&
        (month === 'All' || Number(c.dateReceived.slice(5,7)) === Number(month)) &&
        (status === 'All' || c.status === status) &&
        (type === 'All' || c.caseType === type) &&
        (dept === 'All' || c.department === dept)
    );
}
function isOverdue(c){return c.status!=='Closed' && new Date(c.targetClosure+'T23:59:59')<new Date()}
function renderDashboard(){let data=dashboardFiltered();data.forEach(c=>{if(isOverdue(c))c.status='Overdue'});const total=data.length,open=data.filter(x=>x.status==='Open').length,progress=data.filter(x=>x.status==='In Progress').length,closed=data.filter(x=>x.status==='Closed').length,overdue=data.filter(x=>x.status==='Overdue').length;const resp=data.filter(x=>x.responseHours!=null);const closure=data.filter(x=>x.closureDays!=null);const rated=data.filter(x=>x.rating>0);const avgResp=resp.length?(resp.reduce((a,b)=>a+b.responseHours,0)/resp.length).toFixed(1):'0.0';const avgClosure=closure.length?(closure.reduce((a,b)=>a+b.closureDays,0)/closure.length).toFixed(1):'0.0';const sat=rated.length?(rated.reduce((a,b)=>a+b.rating,0)/rated.length).toFixed(1):'0.0';const closureRate=total?Math.round(closed/total*100):0;
const kpis=[['Total Cases',total,'▤','#eaf3ff','#176be6','Current selection'],['Open Cases',open,'□','#fff0f0','#df4047','Requires response'],['In Progress',progress,'◫','#fff4df','#e59319','Under review'],['Closed Cases',closed,'✓','#e8f8f1','#14a66f',closureRate+'% closure rate'],['Satisfaction',sat+' / 5','★','#e8f8f1','#14a66f',rated.length+' rated cases']];document.getElementById('kpiGrid').innerHTML=kpis.map(k=>`<div class="kpi card" style="--wash:${k[3]};--tone:${k[4]}"><div class="kpi-head"><span class="kpi-icon">${k[2]}</span>${k[0]}</div><div class="kpi-value">${k[1]}</div><div class="kpi-foot">${k[5]}</div></div>`).join('');
renderLine(data);renderDept(data);renderDonut(data);renderSat(data);renderRecent(data);renderAlerts(data);document.getElementById('notifCount').textContent=overdue;}
function monthCounts(data){return Array.from({length:12},(_,i)=>data.filter(c=>Number(c.dateReceived.slice(5,7))===i+1).length)}
function renderLine(data){const vals=monthCounts(data),max=Math.max(4,...vals);const points=vals.map((v,i)=>`${i*(100/11)},${100-v/max*88}`).join(' ');document.getElementById('lineChart').innerHTML=`<div class="chart-grid"></div><div class="axis-y"><span>${max}</span><span>${Math.round(max*.67)}</span><span>${Math.round(max*.33)}</span><span>0</span></div><svg viewBox="0 0 100 100" preserveAspectRatio="none"><polyline fill="none" stroke="#176be6" stroke-width="2.2" points="${points}" vector-effect="non-scaling-stroke"/><polyline fill="none" stroke="#aab9cc" stroke-width="1.3" stroke-dasharray="4 4" points="0,76 9,68 18,72 27,55 36,61 45,47 54,53 63,39 72,45 81,31 90,37 100,28" vector-effect="non-scaling-stroke"/>${vals.map((v,i)=>`<circle cx="${i*(100/11)}" cy="${100-v/max*88}" r="1.6" fill="#176be6" vector-effect="non-scaling-stroke"/>`).join('')}</svg><div class="months">${['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'].map(m=>`<span>${m}</span>`).join('')}</div>`}
function countBy(data,key){return Object.entries(data.reduce((o,x)=>(o[x[key]]=(o[x[key]]||0)+1,o),{})).sort((a,b)=>b[1]-a[1])}
function renderDept(data){const rows=countBy(data,'department').slice(0,5),max=rows[0]?.[1]||1;document.getElementById('deptChart').innerHTML=rows.length?rows.map(([n,v])=>`<div class="hbar-row"><span>${n}</span><div class="hbar-track"><div class="hbar" style="width:${v/max*100}%"></div></div><strong>${v}</strong></div>`).join(''):'<div class="empty">No data</div>'}
function renderDonut(data){const rows=countBy(data,'caseType');const total=data.length||1;let current=0,segments=[];rows.forEach(([n,v],i)=>{const next=current+v/total*100;segments.push(`${colors[i%colors.length]} ${current}% ${next}%`);current=next});document.getElementById('typeDonut').style.background=`conic-gradient(${segments.join(',')||'#e8edf4 0 100%'})`;document.getElementById('donutTotal').textContent=data.length;document.getElementById('typeLegend').innerHTML=rows.slice(0,5).map(([n,v],i)=>`<div class="legend-item"><span class="legend-dot" style="background:${colors[i]}"></span><span>${n}</span><strong>${v}</strong></div>`).join('')}
function renderSat(data){const vals=Array.from({length:12},(_,i)=>{const r=data.filter(c=>Number(c.dateReceived.slice(5,7))===i+1&&c.rating>0);return r.length?r.reduce((a,b)=>a+b.rating,0)/r.length:0});document.getElementById('satChart').innerHTML=vals.map((v,i)=>`<div class="sat-col" style="height:${Math.max(3,v/5*100)}%" title="${v.toFixed(1)} / 5"><span>${['J','F','M','A','M','J','J','A','S','O','N','D'][i]}</span></div>`).join('')}
function renderRecent(data){document.getElementById('recentBody').innerHTML=[...data].sort((a,b)=>b.dateReceived.localeCompare(a.dateReceived)).slice(0,5).map(c=>`<tr><td class="case-link" onclick="openCase('${c.id}')">${c.id}</td><td>${c.department}</td><td>${c.caseType}</td><td>${statusBadge(c.status)}</td><td>${c.pic}</td><td>${fmtDate(c.targetClosure)}</td></tr>`).join('')||'<tr><td colspan="6" class="empty">No cases found.</td></tr>'}
function renderAlerts(data){const over=data.filter(c=>c.status==='Overdue'||isOverdue(c));document.getElementById('overdueBadge').textContent=over.length;document.getElementById('alertList').innerHTML=over.slice(0,6).map(c=>{const days=Math.ceil((new Date()-new Date(c.targetClosure+'T00:00:00'))/86400000);return `<div class="alert-row" onclick="openCase('${c.id}')" style="cursor:pointer"><div><strong>${c.id}</strong><span>${c.department} · ${c.pic}</span></div><span class="alert-due">Due ${days>0?days+' day'+(days>1?'s':'')+' ago':'today'}</span></div>`}).join('')||'<div class="empty">No overdue cases.</div>'}
function filteredLog(){const q=caseSearch.value.toLowerCase().trim();return cases.filter(c=>(logSite.value==='All'||c.site===logSite.value)&&(logStatus.value==='All'||c.status===logStatus.value)&&(logType.value==='All'||c.caseType===logType.value)&&(logDept.value==='All'||c.department===logDept.value)&&(!q||Object.values(c).join(' ').toLowerCase().includes(q))).sort((a,b)=>b.dateReceived.localeCompare(a.dateReceived))}
function renderCaseLog(){const data=filteredLog();document.getElementById('caseTableBody').innerHTML=data.map(c=>`<tr><td class="case-link" onclick="openCase('${c.id}')">${c.id}</td><td>${fmtDate(c.dateReceived)}</td><td>${c.channel}</td><td>${c.messageType}</td><td>${c.caseType}</td><td>${c.department}</td><td>${c.pic}</td><td>${statusBadge(c.status)}</td><td>${priorityBadge(c.priority)}</td><td>${c.responseHours==null?'-':c.responseHours+' h'}</td><td>${c.closureDays==null?'-':c.closureDays+' d'}</td></tr>`).join('')||'<tr><td colspan="11" class="empty">No cases match the filters.</td></tr>';document.getElementById('tableCount').textContent=`Showing ${data.length} of ${cases.length} cases`}
function openCase(id){currentCaseId=id;renderCaseDetail();showPage('case-detail')};window.openCase=openCase;
function renderCaseDetail(){const c=cases.find(x=>x.id===currentCaseId)||cases[0];if(!c)return;detailId.textContent=c.id;detailStatus.innerHTML=statusBadge(c.status);detailMeta.innerHTML=`<span>◷ Received: ${fmtDate(c.dateReceived)}</span><span>◉ Channel: ${c.channel}</span><span>◆ Priority: ${c.priority}</span><span>⌾ Confidential: ${c.confidential}</span>`;const steps=['Received','Responded','Investigated','Action Taken','Verified','Closed'];let completed=c.status==='Open'?1:c.status==='In Progress'||c.status==='Overdue'?3:6;timeline.innerHTML=steps.map((s,i)=>`<div class="time-step ${i<completed?'done':''}"><span class="time-dot">${i<completed?'✓':'•'}</span><h5>${s}</h5><p>${i===0?fmtDate(c.dateReceived):i===1&&c.dateResponse?fmtDate(c.dateResponse):i===5&&c.dateClosed?fmtDate(c.dateClosed):i<completed?'Recorded in demo':'Pending'}<br>${i<completed?'By '+(i===0?'System':c.pic):''}</p></div>`).join('');detailBoxes.innerHTML=`<div class="detail-box"><h4>Original Message</h4><p>${c.confidential==='Yes'?'🔒 Confidential case — restricted content.':c.message}</p></div><div class="detail-box"><h4>Management Response</h4><p>${c.managementResponse||'No management response recorded yet.'}</p></div><div class="detail-box"><h4>Root Cause</h4><p>${c.rootCause||'Root cause analysis is pending.'}</p></div><div class="detail-box"><h4>Corrective Action</h4><p>${c.correctiveAction||'Corrective action has not been entered.'}</p></div><div class="detail-box"><h4>Evidence</h4><div class="evidence-grid">${Array.from({length:c.evidence||0},(_,i)=>`<div class="evidence">▧</div>`).join('')}<div class="add-evidence">＋<br>Add Evidence</div></div></div><div class="detail-box"><h4>Verification</h4><p>${c.status==='Closed'?'Corrective action has been verified and the case is closed.':'Verification will be completed after the corrective action.'}</p></div><div class="detail-box full"><h4>Satisfaction</h4><div class="stars">${c.rating?'★'.repeat(c.rating)+'☆'.repeat(5-c.rating):'☆☆☆☆☆'}</div><p>${c.satisfaction==='No Rating'?'Worker satisfaction has not been recorded.':'Satisfaction: '+c.satisfaction+' · Rating '+c.rating+'/5'}</p></div>`;caseInfoList.innerHTML=[['Site',c.site],['Department',c.department],['PIC',c.pic],['Case Type',c.caseType],['Priority',c.priority],['Target Response',fmtDate(c.targetResponse)],['Target Closure',fmtDate(c.targetClosure)],['Response Time',c.responseHours==null?'-':c.responseHours+' hours'],['Closure Time',c.closureDays==null?'-':c.closureDays+' days'],['Repeated Case',c.repeated],['Confidential',c.confidential]].map(x=>`<div class="info-item"><small>${x[0]}</small><strong>${x[1]}</strong></div>`).join('')}
function renderFollowBoard(){const groups=[['Open','Open'],['In Progress','In Progress'],['Overdue','Overdue'],['Closed','Closed']];followBoard.innerHTML=groups.map(([label,status])=>{const list=cases.filter(c=>c.status===status);return `<div class="board-col"><div class="board-head"><h4>${label}</h4><span class="count">${list.length}</span></div>${list.map(c=>`<div class="case-card" onclick="openCase('${c.id}')"><h5>${c.id}</h5><p>${c.confidential==='Yes'?'Confidential case':c.message.slice(0,90)+(c.message.length>90?'…':'')}</p><div class="case-card-foot"><span>${c.department}</span><span>${fmtDate(c.targetClosure)}</span></div></div>`).join('')||'<div class="empty">No cases</div>'}</div>`}).join('')}
function renderMaster(){const map=[['Channels','channels'],['Message Types','messageTypes'],['Case Types','caseTypes'],['Departments','departments'],['PIC List','pics']];masterGrid.innerHTML=map.map(([title,key])=>`<div class="master-card card"><div class="card-title"><h3>${title}</h3><small>${master[key].length} values</small></div><div class="chip-list">${master[key].map(v=>`<span class="chip">${v}<button title="Demo only">×</button></span>`).join('')}</div><div class="master-add"><input placeholder="Add new value"><button class="btn btn-sm btn-primary" onclick="toast('Master data changes will be stored in the final database version.')">Add</button></div></div>`).join('')}
function renderUsers(){const users=[['AD','Administrator','System Administrator','All Sites'],['MA','Maya Putri','Grievance Administrator','All Sites'],['RA','Rika Amelia','Department PIC','Cijerah'],['DK','Dedi Kurniawan','Department PIC','Majalaya'],['EI','Efie Indrianti','Management Access','All Sites'],['IS','Iwan Setiawan','Department PIC','Cijerah']];usersGrid.innerHTML=users.map(u=>`<div class="user-card card"><div class="user-avatar">${u[0]}</div><h4>${u[1]}</h4><p>${u[3]}</p><span class="role-tag">${u[2]}</span><div><button class="btn btn-sm">View Access</button></div></div>`).join('')}
function nextId(){const year=new Date().getFullYear();const nums=cases.filter(c=>c.id.includes(year)).map(c=>Number(c.id.split('-').pop())).filter(Number.isFinite);return `GRV-${year}-${String((Math.max(0,...nums)+1)).padStart(4,'0')}`}
function addCase(e){e.preventDefault();const f=new FormData(e.target);const rec={id:nextId(),site:f.get('site'),dateReceived:f.get('dateReceived'),dateResponse:f.get('managementResponse')?todayISO():'',dateClosed:f.get('status')==='Closed'?todayISO():'',channel:f.get('channel'),messageType:f.get('messageType'),message:f.get('message'),managementResponse:f.get('managementResponse'),department:f.get('department'),caseType:f.get('caseType'),rootCause:'',correctiveAction:f.get('correctiveAction'),status:f.get('status'),repeated:f.get('repeated'),satisfaction:'No Rating',rating:0,pic:f.get('pic'),priority:f.get('priority'),confidential:f.get('confidential'),targetResponse:f.get('targetResponse'),targetClosure:f.get('targetClosure'),responseHours:f.get('managementResponse')?1:null,closureDays:null,evidence:0};cases.unshift(rec);save();currentCaseId=rec.id;e.target.reset();const fEl=document.getElementById('newCaseForm');fEl.dateReceived.value=todayISO();const nd=new Date();nd.setDate(nd.getDate()+1);fEl.targetResponse.value=nd.toISOString().slice(0,10);nd.setDate(nd.getDate()+6);fEl.targetClosure.value=nd.toISOString().slice(0,10);toast(`${rec.id} created successfully.`);renderCaseDetail();showPage('case-detail')}
function exportCSV(){const headers=['Case ID','Site','Date Received','Date Response','Date Closed','Channel','Message Type','Original Message','Management Response','Department Responsible','Case Type','Root Cause','Corrective Action','Status','Repeated Case','Satisfaction','Rating','PIC','Priority','Confidential','Target Response','Target Closure','Response Time Hours','Closure Days'];const rows=cases.map(c=>[c.id,c.site,c.dateReceived,c.dateResponse,c.dateClosed,c.channel,c.messageType,c.message,c.managementResponse,c.department,c.caseType,c.rootCause,c.correctiveAction,c.status,c.repeated,c.satisfaction,c.rating,c.pic,c.priority,c.confidential,c.targetResponse,c.targetClosure,c.responseHours,c.closureDays]);const esc=v=>`"${String(v??'').replaceAll('"','""')}"`;const csv=[headers.map(esc).join(','),...rows.map(r=>r.map(esc).join(','))].join('\n');downloadBlob(csv,'grievance_case_log_demo.csv','text/csv;charset=utf-8');toast('Case log exported to CSV.')};window.exportCSV=exportCSV;
function downloadBlob(content,name,type){const a=document.createElement('a');a.href=URL.createObjectURL(new Blob([content],{type}));a.download=name;a.click();setTimeout(()=>URL.revokeObjectURL(a.href),1000)}
function downloadReport(type){const data=type==='confidential'?cases.map(c=>({...c,message:c.confidential==='Yes'?'[RESTRICTED]':c.message})):cases;const content=`PT KAHATEX GRIEVANCE MECHANISM\n${type.toUpperCase()} REPORT\nGenerated: ${new Date().toLocaleString()}\n\nTotal cases: ${data.length}\nClosed: ${data.filter(x=>x.status==='Closed').length}\nOpen / ongoing: ${data.filter(x=>x.status!=='Closed').length}\nRepeated cases: ${data.filter(x=>x.repeated==='Yes').length}\n\nThis text preview represents the report module. The full build can generate formatted Excel and PDF reports.`;downloadBlob(content,`grievance_${type}_report.txt`,'text/plain');toast('Demo report generated.')};window.downloadReport=downloadReport;
function importData(file){const reader=new FileReader();reader.onload=()=>{try{if(file.name.endsWith('.json')){const x=JSON.parse(reader.result);if(!Array.isArray(x))throw Error();cases=x.concat(cases);save();renderCaseLog();toast(`${x.length} JSON records imported.`)}else{toast('CSV/Excel field mapping will be finalized after the demo is approved.')}}catch{toast('The selected file could not be imported.')}};reader.readAsText(file)}
function advanceStatus(){const c=cases.find(x=>x.id===currentCaseId);if(!c)return;const next={Open:'In Progress','In Progress':'Closed',Overdue:'Closed',Closed:'Closed'}[c.status];if(next===c.status)return toast('This case is already closed.');c.status=next;if(next==='Closed'){c.dateClosed=todayISO();c.closureDays=Math.max(0,Math.ceil((new Date(c.dateClosed)-new Date(c.dateReceived))/86400000));c.satisfaction='Yes';c.rating=4}save();renderCaseDetail();toast(`Status updated to ${next}.`)}
function addUpdate(){const c=cases.find(x=>x.id===currentCaseId);if(!c)return;const text=prompt('Enter management update:');if(text){c.managementResponse=(c.managementResponse?c.managementResponse+'\n\n':'')+text;if(!c.dateResponse)c.dateResponse=todayISO();if(c.status==='Open')c.status='In Progress';save();renderCaseDetail();toast('Case update saved.')}}
function bind() {
  // 1. Sidebar Nav (Aman karena pakai querySelectorAll)
  document.querySelectorAll('.nav-btn').forEach(b => b.onclick = () => showPage(b.dataset.page));
  
  // 2. Filter Dashboard
  ['dashSite','dashYear','dashMonth','dashStatus','dashType','dashDept'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.onchange = renderDashboard;
  });

  const btnResetDash = document.getElementById('resetDash');
  if (btnResetDash) {
    btnResetDash.onclick = () => {
      document.getElementById('dashSite').value = 'All'; 
      document.getElementById('dashMonth').value = 'All'; 
      document.getElementById('dashStatus').value = 'All'; 
      document.getElementById('dashType').value = 'All'; 
      document.getElementById('dashDept').value = 'All';
      renderDashboard();
    };
  }

  // 3. Filter Case Log
  ['caseSearch','logSite','logStatus','logType','logDept'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener(id === 'caseSearch' ? 'input' : 'change', renderCaseLog);
  });

  const btnClearLog = document.getElementById('clearLog');
  if (btnClearLog) {
    btnClearLog.onclick = () => {
      document.getElementById('caseSearch').value = ''; 
      document.getElementById('logSite').value = 'All'; 
      document.getElementById('logStatus').value = 'All'; 
      document.getElementById('logType').value = 'All'; 
      document.getElementById('logDept').value = 'All';
      renderCaseLog();
    };
  }

  // 4. Tombol & Form Lainnya (Pake Opsional Chaining & Pengecekan)
  document.getElementById('newCaseForm')?.addEventListener('submit', addCase);
  
  const btnDraft = document.getElementById('draftBtn');
  if (btnDraft) btnDraft.onclick = () => toast('Draft saved locally for this demo.');
  
  const btnExport = document.getElementById('exportBtn');
  if (btnExport) btnExport.onclick = exportCSV;
  
  const btnImport = document.getElementById('importBtn');
  const fileImport = document.getElementById('importFile');
  if (btnImport && fileImport) {
    btnImport.onclick = () => fileImport.click();
    fileImport.onchange = e => e.target.files[0] && importData(e.target.files[0]);
  }
  
  const btnAdvance = document.getElementById('advanceBtn');
  if (btnAdvance) btnAdvance.onclick = advanceStatus;
  
  const btnEditResp = document.getElementById('editResponseBtn');
  if (btnEditResp) btnEditResp.onclick = addUpdate;
  
  const btnOpenSide = document.getElementById('openSidebar');
  if (btnOpenSide) btnOpenSide.onclick = () => document.getElementById('sidebar')?.classList.add('open');
  
  const btnCloseSide = document.getElementById('closeSidebar');
  if (btnCloseSide) btnCloseSide.onclick = () => document.getElementById('sidebar')?.classList.remove('open');
  
  const btnLang = document.getElementById('langBtn');
  if (btnLang) {
    btnLang.onclick = () => {
      currentLang = currentLang === 'ID' ? 'EN' : 'ID';
      btnLang.textContent = currentLang === 'ID' ? 'EN' : 'ID';
      toast(currentLang === 'ID' ? 'Bahasa Indonesia active. English copy can be completed in final build.' : 'English interface active for key screens.');
    };
  }

  // 5. Switches di Settings
  document.querySelectorAll('.switch').forEach(s => s.onclick = () => s.classList.toggle('on'));
}
initSelects();bind();renderDashboard();renderCaseLog();renderFollowBoard();renderMaster();renderUsers();