import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Login from "./components/Login";
import Register from "./components/Register";
import AdminPage from "./components/AdminPage";
import Showcase from "./components/Showcase";
import "./App.css";

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/admin" element={<AdminPage />} />
        <Route path="/showcase" element={<Showcase />} />
      </Routes>
    </Router>
  );
}

export default App;
