import React, { useState, useEffect } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import "./Showcase.css"; // Mengimpor file CSS

const ShowcasePage = () => {
  const [cars, setCars] = useState([]);
  const navigate = useNavigate(); // Hook untuk navigasi

  // Logout function
  const handleLogout = () => {
    localStorage.removeItem("loggedIn"); // Menghapus status login dari localStorage
    navigate("/"); // Mengarahkan pengguna ke halaman login
  };

  // Fetch data mobil
  const fetchCars = async () => {
    try {
      const response = await axios.get("http://localhost/server/vga.php");
      setCars(response.data);
    } catch (error) {
      console.error("Error fetching cars:", error);
    }
  };

  useEffect(() => {
    fetchCars();
  }, []);

  return (
    <div className="showcase-container">
      <h2 className="page-title">Showcase Page</h2>
      <button className="logout-button" onClick={handleLogout}>
        Logout
      </button> 

      <div className="car-table-container">
        <table className="car-table">
          <thead>
            <tr>
              <th>Nama vga</th>
              <th>Merek</th>
              <th>Tanggal Rilis</th>
              <th>Harga</th>
              <th>Foto</th>
            </tr>
          </thead>
          <tbody>
            {cars.map((car) => (
              <tr key={car.id}>
                <td>{car.nama_vga}</td>
                <td>{car.merek}</td>
                <td>{car.tanggal_rilis}</td>
                <td>Rp. {car.harga.toLocaleString("id-ID")}</td>
                <td>
                  {car.foto_url ? (
                    <img
                      src={car.foto_url}
                      alt="Foto Mobil"
                      className="car-image"
                    />
                  ) : (
                    <span>No Foto</span>
                  )}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default ShowcasePage;
