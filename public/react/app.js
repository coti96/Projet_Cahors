const React = require('react');
const ReactDOM = require('react-dom');
const axios = require('axios');

function TaskList() {
  const [tasks, setTasks] = React.useState([]);
  const [isLoading, setIsLoading] = React.useState(true);

  React.useEffect(() => {
    axios.get('http://localhost:8000/api/tasks')
      .then(response => {
        setTasks(response.data);
        setIsLoading(false);
      })
      .catch(error => {
        console.log(error);
        setIsLoading(false);
      });
  }, []);

  return (
    React.createElement('div', null,
      React.createElement('h1', null, 'Ma liste de tâches'),
      isLoading ? (
        React.createElement('p', null, 'Chargement en cours...')
      ) : (
        React.createElement('div', null,
          tasks.length > 0 ? (
            React.createElement('ul', null,
              tasks.map(task => (
                React.createElement('li', { key: task.id },
                  task.title,
                  React.createElement('br', null),
                  task.description,
                  React.createElement('br', null),
                  task.personne,
                  React.createElement('br', null),
                  task.completed ? 'Terminée' : 'En cours'
                )
              ))
            )
          ) : (
            React.createElement('p', null, 'Aucune tâche à afficher pour le moment.')
          )
        )
      )
    )
  );
}

ReactDOM.render(React.createElement(TaskList), document.getElementById('root'));
