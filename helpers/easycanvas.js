class canvasManager{
    constructor(ref){
        this.cvs = document.querySelector(ref);
        this.ctx = this.cvs.getContext("2d");
        this.pr = window.devicePixelRatio;
    }
    
    resize(w,h){
        this.cvs.width = w*this.pr;
        this.cvs.height = h*this.pr;
        
        this.cvs.style.width = w+"px";
        this.cvs.style.height = h+"px";
    }
    
    get w(){
        return(this.cvs.width/this.pr);
    }
    
    get h(){
        return(this.cvs.height/this.pr);
    }
    
    beginPath(){
        this.ctx.beginPath();
    }
    
    closePath(){
        this.ctx.closePath();
    }
    
    fill(){
        this.ctx.fill();
    }
    
    stroke(){
        this.ctx.stroke();
    }
    
    save(){
        this.ctx.save();
    }
    
    restore(){
        this.ctx.restore();
    }
    
    moveTo(x,y){
        this.ctx.moveTo(x*this.pr,y*this.pr);
    }
    
    lineTo(x,y){
        this.ctx.lineTo(x*this.pr,y*this.pr);
    }
    
    arc(a,b,c,d,e,f){
        this.ctx.arc(a*this.pr,b*this.pr,
                    c*this.pr,d,e,f);
    }
    
    fillCircle(a,b,c){
        this.beginPath();
        this.moveTo(a+c,b);
        this.arc(a,b,c,0,2*Math.PI,false);
        this.fill();
    }
    
    fillRect(x,y,w,h){
        this.ctx.fillRect(x*this.pr,y*this.pr,
                         w*this.pr,h*this.pr);
    }
    
    clearRect(x,y,w,h){
        this.ctx.clearRect(x*this.pr,y*this.pr,
                         w*this.pr,h*this.pr);
    }
    
    quadraticCurveTo(x,y,w,h){
        this.ctx.quadraticCurveTo(x*this.pr,y*this.pr,
                         w*this.pr,h*this.pr);
    }
    
    bezierCurveTo(x,y,w,h,a,b){
        this.ctx.quadraticCurveTo(x*this.pr,y*this.pr,
                         w*this.pr,h*this.pr,
                                 a*this.pr,b*this.pr);
    }
    
    set lineWidth(r){
        this.ctx.lineWidth = r*this.pr;
    }
}