const articles = document.getElementById('post');

if(articles) {
    articles.addEventListener('click', e => {
        if (e.target.className === 'delete-btn' ) 
        {
          if(confirm('Are you sure?')) {
              const id = e.target.getAttribute('data-id');

              fetch(`post/delete/${id}`, {
                  method: 'DELETE'
              }).then(res => window.location.reload());
          }
        }
    });
}
