

export default function BoxOfPost({content , title,id,onclick}) {
    function onClickHandler(id){
        console.log(id);
        
    }
  return (
    <div className='box'>
        <button onClick={()=>onclick(id)}>{title}</button>
        <input value='in the name '/>
        {`this is the ${content} and ${id} and also ${title}`}
    </div>
  )
}
