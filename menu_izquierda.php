<!-- Contenido del menú izquierdo (categorías y subcategorías) -->
<nav id="menu-izquierda">
    <h2>Filtros</h2>  

    <!-- Escala -->
    <label for="escala">Escala:</label>
    <select id="escala" name="escala">
        <option value="1:18">1:18</option>
        <option value="1:24">1:24</option>
        <option value="1:43">1:43</option>
    </select>

    <!-- Marca -->
    <label for="marca">Marca:</label>
    <select id="marca" name="marca">
    <option value="Ferrari">Ferrari</option>
    <option value="Mustang">Mustang</option>
    <option value="Ford">Ford</option>
    <option value="Chevrolet">Chevrolet</option>
    <option value="BMW">BMW</option>
    <option value="Mercedes-Benz">Mercedes-Benz</option>
    <option value="Audi">Audi</option>
    <option value="Porsche">Porsche</option>
    <option value="Harley-Davidson">Harley-Davidson</option>
    <option value="Honda">Honda</option>
    <option value="Yamaha">Yamaha</option>
    <option value="Ducati">Ducati</option>
    <option value="Kawasaki">Kawasaki</option>
    <option value="Suzuki">Suzuki</option>
    <option value="BMW Motorrad">BMW Motorrad</option>
    <option value="Triumph">Triumph</option>
    </select>

    <!-- Tipo de Vehículo -->
    <label for="tipo-vehiculo">Tipo de Vehículo:</label>
    <select id="tipo-vehiculo" name="tipo-vehiculo">
        <option value="Coche">Coche</option>
        <option value="Camion">Camión</option>
        <option value="Moto">Moto</option>
        
    </select>

    <!-- Colecciones -->
    <label for="colecciones">Colecciones:</label>
    <select id="colecciones" name="colecciones">
        <option value="MotoGP">Moto GP</option>
        <option value="Formula1">Formula 1</option>
        <option value="Clasicos">Clásicos</option>
        <option value="Antano">Antaño</option>
        <option value="Americanos">Americanos</option>
        
    </select>

    <!-- Precio -->
    <label for="precio">Precio:</label>
    <input type="range" id="precio" name="precio" min="0" max="1000" value="0" step="10">
    <span id="precio-valor">0 €</span>

    <button id="button">Aplicar</button>
</nav>
