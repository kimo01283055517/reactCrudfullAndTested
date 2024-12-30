
import { useState, useEffect } from '@wordpress/element';
import axios from 'axios';
import BoxOfPost from './component/BoxOfPost';

const App = () => {
    const [data, setData] = useState([]);
    const [selectedItem, setSelectedItem] = useState(null);
  //
    useEffect(() => {
      const fetchData = async () => {
        try {
          const response = await axios.get(`${my.root}wp/v2/posts`, {
            headers: {
              'X-WP-Nonce': my.nonce,
            },
          });
          console.log(response.data);
          setData(response.data);
        } catch (error) {
          console.error('Error fetching data:', error);
        }
      };
  
      fetchData();
    }, []);

    // indsfgsert one item useing form in react and update the state item
    const handleInterst = async ( updatedItem) => {
        console.log(updatedItem);
      try {
        const response = await axios.post(`${my.root}wp/v2/posts/`, updatedItem, {
          headers: {
            'X-WP-Nonce': my.nonce,
          },
        });
         setData((prevData) => [...prevData,response.data]  );
        console.log(response.data)
        alert('Item inserted successfuy!');
      } catch (error) {
        console.error('Error updating item:', error);
        console.log('just test')
      }
    };
  
    // Update an item
    const handleEdit = async (id, updatedItem) => {
        console.log(updatedItem);
      try {
        const response = await axios.put(`${my.root}wp/v2/posts/${id}`, updatedItem, {
          headers: {
            'X-WP-Nonce': my.nonce,
          },
        });
        setData((prevData) =>
          prevData.map((item) => (item.id === id ? response.data : item))
          
        );
        console.log(response.data)
        alert('Item updated successfully!');
      } catch (error) {
        console.error('Error updating item:', error);
      }
    };
  
    // Delete an item__________________________________________________________________________________________________
    const handleDelete = async (id) => {
      try {
        await axios.delete(`${my.root}wp/v2/posts/${id}`, {
          headers: {
            'X-WP-Nonce': my.nonce,
          },
        });
        setData((prevData) => prevData.filter((item) => item.id !== id));
        alert('Item deleted successfully!');
      } catch (error) {
        console.error('Error deleting item:', error);
      }
    };
  
    return (
      <div>
        <h1>add new</h1>
        <div>
        <form
              onSubmit={(e) => {
                console.log('run as defualt')
                e.preventDefault();
                const updatedItem = { title: e.target.name.value ,status: 'publish',content: e.target.content.value };
                handleInterst( updatedItem);
               
              }}
            >
              <input
                type="text"
                name="name"
                defaultValue=''
                required
              />
              <input
                type="text"
                name="content"
                defaultValue=''
                required
              />
              <button type="submit">Save</button>
            </form>
            </div>

        <h1>Data List</h1>
        <ul>
          {data.map((item) => (
            <li key={item.id}>
              {item.title.rendered}
              <button onClick={() => setSelectedItem(item)}>Edit</button>
              <button onClick={() => handleDelete(item.id)}>Delete</button>
            </li>
          ))}
        </ul>
  
        {selectedItem && (
          <div>
            <h2>Edit Item</h2>
            <form
              onSubmit={(e) => {
                e.preventDefault();
                const updatedItem = { title: e.target.name.value ,status: 'publish',content: 'This is the content of the new post.' };
                handleEdit(selectedItem.id, updatedItem);
                setSelectedItem(null);
              }}
            >
              <input
                type="text"
                name="name"
                defaultValue={selectedItem.name}
                required
              />
              <button type="submit">Save</button>
            </form>
          </div>
        )}
      </div>
    );
  };
  
  export default App;