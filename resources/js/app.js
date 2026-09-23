import './bootstrap';


const filters=[...document.querySelectorAll('.filter')];
const cards=[...document.querySelectorAll('.event-card')];
filters.forEach(btn=>btn.addEventListener('click',()=>{
  filters.forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
  const f=btn.dataset.filter;
  cards.forEach(c=>c.hidden=!(f==='all'||c.dataset.cat===f));
}));

document.querySelectorAll('.amount').forEach(btn=>btn.addEventListener('click',()=>{
  document.querySelectorAll('.amount').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
}));

const newsletterForm=document.querySelector('.newsletter');
newsletterForm?.addEventListener('submit',event=>{
  event.preventDefault();
  alert('Gracias por suscribirte');
});

const menuButton=document.querySelector('#menuBtn');
const navigation=document.querySelector('.nav-links');

menuButton?.addEventListener('click',()=>{
  const isOpen=navigation?.classList.toggle('is-open') ?? false;
  menuButton.setAttribute('aria-expanded',String(isOpen));
});

navigation?.querySelectorAll('a').forEach(link=>link.addEventListener('click',()=>{
  navigation.classList.remove('is-open');
  menuButton?.setAttribute('aria-expanded','false');
}));

const heroSlides=[...document.querySelectorAll('[data-hero-slide]')];
const heroDots=[...document.querySelectorAll('[data-hero-dot]')];
const heroPrevious=document.querySelector('[data-hero-prev]');
const heroNext=document.querySelector('[data-hero-next]');
let activeHeroSlide=0;
let heroTimer;

const showHeroSlide=index=>{
  if(heroSlides.length<2) return;
  activeHeroSlide=(index+heroSlides.length)%heroSlides.length;
  heroSlides.forEach((slide,slideIndex)=>{
    const isActive=slideIndex===activeHeroSlide;
    slide.classList.toggle('is-active',isActive);
    slide.setAttribute('aria-hidden',String(!isActive));
  });
  heroDots.forEach((dot,dotIndex)=>dot.classList.toggle('is-active',dotIndex===activeHeroSlide));
};

const startHeroSlider=()=>{
  if(heroSlides.length<2) return;
  window.clearInterval(heroTimer);
  heroTimer=window.setInterval(()=>showHeroSlide(activeHeroSlide+1),6500);
};

heroPrevious?.addEventListener('click',()=>{showHeroSlide(activeHeroSlide-1);startHeroSlider();});
heroNext?.addEventListener('click',()=>{showHeroSlide(activeHeroSlide+1);startHeroSlider();});
heroDots.forEach(dot=>dot.addEventListener('click',()=>{showHeroSlide(Number(dot.dataset.heroDot));startHeroSlider();}));
startHeroSlider();

const copyShareUrl=async value=>{
  if(navigator.clipboard?.writeText){
    await navigator.clipboard.writeText(value);
    return;
  }

  const field=document.createElement('textarea');
  field.value=value;
  field.setAttribute('readonly','');
  field.style.position='fixed';
  field.style.opacity='0';
  document.body.appendChild(field);
  field.select();
  document.execCommand('copy');
  field.remove();
};

document.querySelectorAll('[data-share-native]').forEach(button=>{
  button.addEventListener('click',async()=>{
    const shareData={
      title:button.dataset.shareTitle,
      text:button.dataset.shareText,
      url:button.dataset.shareUrl,
    };

    if(navigator.share){
      try{await navigator.share(shareData);}catch(error){
        if(error?.name!=='AbortError') console.error(error);
      }
      return;
    }

    await copyShareUrl(shareData.url);
    const previous=button.textContent;
    button.textContent='Enlace copiado';
    window.setTimeout(()=>button.textContent=previous,1800);
  });
});

document.querySelectorAll('[data-copy-link]').forEach(button=>{
  button.addEventListener('click',async()=>{
    await copyShareUrl(button.dataset.shareUrl);
    const previous=button.textContent;
    button.textContent='Copiado';
    window.setTimeout(()=>button.textContent=previous,1800);
  });
});
