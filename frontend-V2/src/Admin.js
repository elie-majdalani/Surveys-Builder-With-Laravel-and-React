import React, { useState } from 'react';

const Admin = ({ setReload }) => {
  const [questionType, setQuestionType] = useState(0);
  const [questionName, setQuestionName] = useState();
  const [question, setQuestion] = useState();
  const [options, setOptions] = useState([{ value: '' }]);

  const addQuestion = async () => {
    if (question) return
    try {
      const res = await fetch(`http://127.0.0.1:8000/api/auth/addQuestion?question=${questionName}&type=${questionType}`);
      const data = await res.json();
      const newQuestion = data.data;
      setQuestion(newQuestion);

    } catch (err) {

      console.log(err);
    }
  }
  const addOptions = () => {
    setOptions([...options, {
      value: '',
    }])
  }
  const handleChangeOptions = (index, event) => {
    const newOptions = [...options];
    newOptions[index] = event.target.value;
    setOptions(newOptions);
  }
  const saveOptions = async () => {

    try {
      if (options[0].value === '') return
      const res = await fetch(`http://127.0.0.1:8000/api/auth/addOption`, {
        method: 'POST',
        body: JSON.stringify({
          question_id: question.id,
          options,
        })
      });

      const data = await res.json();

      if (data.status === 'success') {
        setOptions([{ value: '' }])
        setQuestion()
        setQuestionName('')
        setReload(true)
      }
      // const question = data.data;
      // setOptions([{ value: '' }])
    } catch (err) {
      console.log(err);
    }

  }
  return (
    <div id="body">
      {question ? <div>
        <label>{questionName}</label>
        {options.map((option, index) => {
          return (
            <>
              <input type="text" required onChange={(event) => handleChangeOptions(index, event)} value={option.value} />
            </>
          )
        })}

        {question.type !== '0' && <button onClick={addOptions}>+</button>}
        <button onClick={saveOptions}>Save</button>
      </div> : <div />}
      <div className="add-question">
        <label>Question</label>
        <input onChange={(e) => setQuestionName(e.currentTarget.value)} value={questionName} type="text" />
        <label>Choose a question type: </label>
        <select className="type" value={questionType} onChange={(e) => setQuestionType(e.currentTarget.value)}>
          <option value="0">Text</option>
          <option value="1">MCQ</option>
          <option value="2">CheckBox</option>
          <option value="3">Dropdown</option>
        </select>
        <button onClick={addQuestion}>+</button>
      </div >
    </div >
  )
}

export default Admin;

