import React, { useRef } from 'react';

const Admin=()=> {
  const ref = useRef(null);
  
  return (
    <div className="add-question">
      <label>Question</label>
      <input id="question" type="text" />
      <label>Choose a question type: </label>
      <select name="type" id="type">
        <option value="0">Text</option>
        <option value="1">MCQ</option>
        <option value="2">CheckBox</option>
        <option value="3">Dropdown</option>
      </select>
      <button ref={ref}>+</button>
    </div>
  )
}

export default Admin;

