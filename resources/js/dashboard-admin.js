const sidebar = document.querySelector("aside");
const toggleSidebar = document.querySelector("#toggle-sidebar");
const toggleTopbar = document.querySelector("#toggle-topbar");
const content = document.querySelector("#content");

console.log({ toggleTopbar, toggleSidebar });

toggleTopbar.addEventListener("click", () => {
    if (window.innerWidth > 1024) {
        content.classList.toggle("lg:ms-64");
        sidebar.classList.toggle("lg:-translate-x-0");
    } else {
        console.log("mobile");
        sidebar.classList.toggle("-translate-x-full");
    }
});

toggleSidebar.addEventListener("click", () => {
    sidebar.classList.toggle("-translate-x-full");
});
