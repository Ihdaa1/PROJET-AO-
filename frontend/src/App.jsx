import React from "react";
import { Routes, Route } from "react-router-dom";
import Home from "./pages/Home";
import Dashboard from "./pages/Dashboard";
import AddAO from "./pages/AddAO";
import ListeAO from "./pages/ListeAO";
import ListePrestataires from "./pages/ListePrestataires";
import ProtectedRoute from "./components/ProtectedRoute";
import AddPrestataire from "./pages/AddPrestataire";

function App() {
  return (
    <Routes>
      <Route path="/" element={<Home />} />
      <Route
        path="/dashboard"
        element={
          <ProtectedRoute>
            <Dashboard />
          </ProtectedRoute>
        }
      />
      <Route
        path="/add-ao"
        element={
          <ProtectedRoute>
            <AddAO />
          </ProtectedRoute>
        }
      />
      <Route
        path="/liste-ao"
        element={
          <ProtectedRoute>
            <ListeAO />
          </ProtectedRoute>
        }
      />
      <Route
        path="/liste-prestataires"
        element={
          <ProtectedRoute>
            <ListePrestataires />
          </ProtectedRoute>
        }
      />
      <Route
        path="/add-prestataire"
        element={
          <ProtectedRoute>
            <AddPrestataire />
          </ProtectedRoute>
        }
      />
    </Routes>
  );
}

export default App;
