document.addEventListener('DOMContentLoaded', function() {
    loadBlogs();

    document.getElementById('createForm').addEventListener('submit', function(e) {
        e.preventDefault();
        createBlog();
    });
});

function createBlog() {
    let title = document.getElementById('title').value;
    let content = document.getElementById('content').value;

    fetch('/index.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=create&title=${title}&content=${content}`
    }).then(response => response.text())
    .then(data => {
        alert(data);
        loadBlogs();
    });
}

function loadBlogs() {
    fetch('/index.php?action=read')
    .then(response => response.json())
    .then(blogs => {
        let blogsHtml = '';
        let userBlogsHtml = '';
        blogs.forEach(blog => {
            blogsHtml += `<div>
                            <h3>${blog.title}</h3>
                            <p>${blog.content}</p>
                            <button onclick="deleteBlog(${blog.id})">Delete</button>
                            <button onclick="editBlog(${blog.id}, '${blog.title}', '${blog.content}')">Edit</button>
                          </div>`;
            userBlogsHtml += `<div>
                                <h3>${blog.title}</h3>
                                <p>${blog.content}</p>
                              </div>`;
        });
        document.getElementById('blogs').innerHTML = blogsHtml;
        document.getElementById('userBlogs').innerHTML = userBlogsHtml;
    });
}

function deleteBlog(id) {
    fetch('/index.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=delete&id=${id}`
    }).then(response => response.text())
    .then(data => {
        alert(data);
        loadBlogs();
    });
}

function editBlog(id, title, content) {
    document.getElementById('title').value = title;
    document.getElementById('content').value = content;

    document.getElementById('createForm').removeEventListener('submit', createBlog);
    document.getElementById('createForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updateBlog(id);
    });
}

function updateBlog(id) {
    let title = document.getElementById('title').value;
    let content = document.getElementById('content').value;

    fetch('/index.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=update&id=${id}&title=${title}&content=${content}`
    }).then(response => response.text())
    .then(data => {
        alert(data);
        loadBlogs();
    });
}
