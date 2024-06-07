// Função para criar um novo item da lista e enviar para a API
async function newElement() {
  var title = document.getElementById("titleInput").value;
  var description = document.getElementById("descriptionInput").value;

  if (title === '' || description === '') {
      alert("You must write something!");
      return;
  }

  var newTask = {
      title: title,
      description: description,
      status: 'pending'  // Definindo o status como 'pending' por padrão
  };

  try {
      const response = await fetch('http://localhost/To-Do-List/To-Do-List-API/public/tasks', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'token': '1234'
          },
          body: JSON.stringify(newTask)
      });
      const data = await response.json();
      console.log(data);

      if (data.response === 'success') {
          newTask.id = data.id;  // Atribui o ID retornado pela API ao objeto newTask
          addTaskToList(newTask);
      } else {
          alert("Error adding task: " + data.msg);
      }
  } catch (error) {
      console.error('Error:', error);
  }

  document.getElementById("titleInput").value = "";
  document.getElementById("descriptionInput").value = "";

  // Chamar fetchTasks para atualizar a lista completa após adicionar um novo item
  fetchTasks();
}

// Função para adicionar um item da lista no DOM
function addTaskToList(task) {
  var li = document.createElement("li");
  li.setAttribute('data-id', task.id);  // Adiciona o ID da tarefa ao elemento <li>
  li.innerHTML = `Título: ${task.title}: Descrição: ${task.description}`;

  var span = document.createElement("SPAN");
  var txt = document.createTextNode("\u00D7");
  span.className = "close";
  span.appendChild(txt);
  li.appendChild(span);

  span.onclick = function () {
      var taskId = li.getAttribute('data-id');
      deleteTask(taskId, li);
  }

  document.getElementById("myUL").appendChild(li);
}

// Função para buscar e exibir tarefas da API
async function fetchTasks() {
  try {
      const response = await fetch('http://localhost/To-Do-List/To-Do-List-API/public/tasks');
      const data = await response.json();
      console.log(data);

      // Limpar a lista antes de adicionar os itens novamente
      const ul = document.getElementById('myUL');
      ul.innerHTML = '';

      data.forEach(task => {
          addTaskToList(task);
      });
  } catch (error) {
      console.error('Error fetching tasks:', error);
  }
}

// Inicializar a lista de tarefas ao carregar a página
document.addEventListener('DOMContentLoaded', fetchTasks);

// Função para deletar um item da lista e do banco de dados
async function deleteTask(id, listItem) {
  try {
      const response = await fetch(`http://localhost/To-Do-List/To-Do-List-API/public/tasks/${id}`, {
          method: 'DELETE',
          headers: {
              'Content-Type': 'application/json',
              'token': '1234'
          }
      });
      const data = await response.json();
      console.log(data);

      if (data.response === 'success') {
          listItem.remove();
      } else {
          alert("Error deleting task: " + data.msg);
      }
  } catch (error) {
      console.error('Error:', error);
  }
}

// Adicionar funcionalidade para marcar itens como completos ou incompletos e atualizar no banco de dados
var list = document.querySelector('ul');
list.addEventListener('click', function (ev) {
  if (ev.target.tagName === 'LI') {
      ev.target.classList.toggle('checked');
      var taskId = ev.target.getAttribute('data-id');
      var newStatus = ev.target.classList.contains('checked') ? 'completed' : 'pending';
      updateTaskStatus(taskId, newStatus);
  }
}, false);

// Função para atualizar o status da tarefa no banco de dados
async function updateTaskStatus(id, status) {
  try {
      const response = await fetch(`http://localhost/To-Do-List/To-Do-List-API/public/tasks/${id}`, {
          method: 'PUT',
          headers: {
              'Content-Type': 'application/json',
              'token': '1234'
          },
          body: JSON.stringify({ status: status })
      });
      const data = await response.json();
      console.log(data);

      if (data.response !== 'success') {
          alert("Error updating task: " + data.msg);
      }
  } catch (error) {
      console.error('Error:', error);
  }
}
