const btns = document.querySelectorAll(".btn");


for (let index = 0; index < btns.length; index++) {
  const btn = btns[index];
  let rippleDiv;
  btn.addEventListener("mouseenter", (e) => {
    //get the coordinates of the cursor
    const left = e.clientX - e.target.getBoundingClientRect().left;
    const top = e.clientY - e.target.getBoundingClientRect().top;
  
    rippleDiv = document.createElement("div");
    rippleDiv.classList.add("ripple");
    rippleDiv.style.left = `${left}px`;
    rippleDiv.style.top = `${top}px`;
  
    btn.prepend(rippleDiv);
  });
  
  btn.addEventListener("mouseleave", () => {
    btn.removeChild(rippleDiv);
  });
}
