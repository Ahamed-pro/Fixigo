
const c=document.getElementById('dots'),x=c.getContext('2d');let W,H,ds=[];
function rsz(){W=c.width=window.innerWidth;H=c.height=window.innerHeight}
function mk(){return{x:Math.random()*W,y:Math.random()*H,vx:(Math.random()-.5)*.4,vy:(Math.random()-.5)*.4,r:Math.random()*1.7+.5,a:Math.random()*.4+.1}}
function init(){ds=[];for(let i=0;i<Math.min(Math.floor(W*H/14000),80);i++)ds.push(mk())}
function drw(){x.clearRect(0,0,W,H);ds.forEach(d=>{d.x+=d.vx;d.y+=d.vy;if(d.x<0||d.x>W)d.vx*=-1;if(d.y<0||d.y>H)d.vy*=-1;x.save();x.globalAlpha=d.a;x.beginPath();x.arc(d.x,d.y,d.r,0,Math.PI*2);x.fillStyle='#FF5C1A';x.fill();x.restore()});requestAnimationFrame(drw)}
rsz();init();drw();window.addEventListener('resize',()=>{rsz();init()});
