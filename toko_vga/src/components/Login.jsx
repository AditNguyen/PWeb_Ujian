import { useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import "./Login.css"; // Mengimpor file CSS

const Login = () => {
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const navigate = useNavigate();

  const handleLogin = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post("http://localhost/server/login.php", {
        username,
        password,
      });
      if (response.data.role === "admin") {
        navigate("/admin");
      } else if (response.data.role === "client") {
        navigate("/showcase");
      } else {
        alert("Invalid credentials");
      }
    } catch (error) {
      alert("Error logging in");
    }
  };

  return (
    <div className="login-container">
      <div className="header">Showroom VGA</div>
      <h2 className="page-title">Login</h2>
      <div className="form-container">
        <form onSubmit={handleLogin}>
          <input
            type="text"
            placeholder="Username"
            value={username}
            onChange={(e) => setUsername(e.target.value)}
            className="input-field"
            required
          />
          <input
            type="password"
            placeholder="Password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            className="input-field"
            required
          />
          <button type="submit" className="button-submit">Login</button>
        </form>
      </div>
      <div className="register-link">
        <p>
          Belum mempunyai akun? <a href="/register">Daftar Disini.</a>
        </p>
      </div>
      <button className="logout-button">Logout</button> {/* Tombol Logout */}
    </div>
  );
};

export default Login;
