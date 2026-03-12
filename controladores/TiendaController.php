<?php
require_once 'modelos/Producto.php';
require_once 'modelos/Genero.php';
require_once 'modelos/Categoria.php';
require_once 'modelos/Banner.php';

class TiendaController
{
    private $menuCategorias;
    private $productoModel;
    private $generoModel;
    private $bannerModel;
    private $categoriaModel;

    public function __construct()
    {
        $this->productoModel = new Producto();
        $this->generoModel = new Genero();
        $this->categoriaModel = new Categoria();
        $this->bannerModel = new Banner();
        $this->menuCategorias = $this->categoriaModel->obtenerActivasAgrupadasPorGenero();
    }

    private function cargarDatosLayout()
    {
        return [
            'menuCategorias' => $this->menuCategorias,
            'bannersActivos' => $this->bannerModel->obtenerActivos()
        ];
    }

    public function home()
    {
        $layout = $this->cargarDatosLayout();
        $menuCategorias = $layout['menuCategorias'];
        $bannersActivos = $layout['bannersActivos'];

        $generos = $this->generoModel->obtenerActivos();
        $nuevos = $this->productoModel->obtenerNuevos(8);
        $destacados = $this->productoModel->obtenerDestacados(8);

        require_once 'vistas/tienda/home.php';
    }

    public function catalogo()
    {
        $layout = $this->cargarDatosLayout();
        $menuCategorias = $layout['menuCategorias'];
        $bannersActivos = $layout['bannersActivos'];

        $genero = $_GET['genero'] ?? null;
        $categoria = $_GET['categoria'] ?? null;

        $generos = $this->generoModel->obtenerActivos();
        $categorias = $this->categoriaModel->obtenerActivasPorGeneroSlug($genero);
        $productos = $this->productoModel->obtenerCatalogo($genero, $categoria);

        $generoSeleccionado = null;
        $categoriaSeleccionada = null;

        foreach ($generos as $item) {
            if ($item['slug'] === $genero) {
                $generoSeleccionado = $item['nombre'];
                break;
            }
        }

        foreach ($categorias as $item) {
            if ($item['slug'] === $categoria) {
                $categoriaSeleccionada = $item['nombre'];
                break;
            }
        }

        require_once 'vistas/tienda/catalogo.php';
    }

    public function producto()
    {
        $layout = $this->cargarDatosLayout();
        $menuCategorias = $layout['menuCategorias'];
        $bannersActivos = $layout['bannersActivos'];

        $slug = $_GET['slug'] ?? null;

        if (!$slug) {
            die('Producto no encontrado.');
        }

        $producto = $this->productoModel->obtenerPorSlugPublico($slug);

        if (!$producto) {
            die('Producto no encontrado.');
        }

        $imagenes = $this->productoModel->obtenerImagenesSecundarias($producto['id']);

        $relacionados = $this->productoModel->obtenerRelacionadosPorCategoria(
            $producto['categoria_id'],
            $producto['id'],
            4
        );

        require_once 'vistas/tienda/producto.php';
    }
}