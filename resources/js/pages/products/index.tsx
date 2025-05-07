import { Alert, AlertDescription } from '@/components/ui/alert';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@headlessui/react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { CirclePlus, Eye, Pencil, Trash2 } from 'lucide-react';
import { useEffect, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Manage Products',
        href: '/products',
    },
];

interface Product {
    id: number;
    name: string;
    description: string;
    price: number;
    featured_image: string;
    created_at: string;
}

export default function Index({ ...props }: { products: Product[] }) {
    const { products } = props;
    const { flash } = usePage<{ flash?: { success?: string; error?: string } }>().props;
    const flashMessage = flash?.success || flash?.error;
    const [showAlert, setShowAlert] = useState(flash?.success || flash?.error ? true : false);

    console.log(flashMessage, flash, showAlert);

    // console.log(products);

    // console.log('flash', flash);

    useEffect(() => {
        if (flashMessage) {
            const timer = setTimeout(() => setShowAlert(false), 3000);
            return () => clearTimeout(timer);
        }
    }, [flashMessage]);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Product Management" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                {/* Alert message */}
                {showAlert && flashMessage && (
                    <Alert variant={'default'} className={` ${flash?.success ? 'bg-green-800' : flash?.error ? 'bg-red-800' : ''} text-white`}>
                        <AlertDescription className="text-white">
                            {flash.success ? 'Success!' : 'Error!'} {flashMessage}
                        </AlertDescription>
                    </Alert>
                )}

                {/* Add product button */}
                <div className="ml-auto">
                    <Link
                        as="button"
                        className="flex cursor-pointer items-center rounded-lg bg-slate-950 px-4 py-2 text-sm text-white hover:opacity-80"
                        href={route('products.create')}
                    >
                        <CirclePlus className="me-2" /> Add Products
                    </Link>
                </div>

                <div className="overflow-hidden rounded-lg border bg-white shadow-sm">
                    <table className="w-full table-auto text-sm">
                        <thead>
                            <tr className="bg-slate-950 text-white">
                                <th className="border p-4">#</th>
                                <th className="border p-4">Name</th>
                                <th className="border p-4">Decription</th>
                                <th className="border p-4">Price (MYR)</th>
                                <th className="border p-4">Featured Image</th>
                                <th className="border p-4">Created Date</th>
                                <th className="border p-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {products.length > 0 ? (
                                products.map((product, index) => (
                                    <tr key={index}>
                                        <td className="border px-4 py-2 text-center">{index + 1}</td>
                                        <td className="border px-4 py-2 text-center">{product.name}</td>
                                        <td className="border px-4 py-2 text-center">{product.description}</td>
                                        <td className="border px-4 py-2 text-center">{product.price}</td>
                                        <td className="flex justify-center px-4 py-2 text-center">
                                            {product.featured_image && (
                                                <img
                                                    src={`/storage/${product.featured_image}`}
                                                    alt={product.name}
                                                    className="h-16 w-16 object-cover"
                                                />
                                            )}
                                        </td>
                                        <td className="border px-4 py-2 text-center">{product.created_at}</td>
                                        <td className="border px-4 py-2 text-center">
                                            <Link
                                                as="button"
                                                className="ms-2 cursor-pointer rounded bg-blue-700 p-2 text-white hover:opacity-80"
                                                alt="Show"
                                                href={route('products.show', product.id)}
                                            >
                                                <Eye size={15} />
                                            </Link>
                                            <Link
                                                as="button"
                                                className="ms-2 cursor-pointer rounded bg-green-700 p-2 text-white hover:opacity-80"
                                                alt="Edit"
                                                href={route('products.edit', product.id)}
                                            >
                                                <Pencil size={15} />
                                            </Link>
                                            <Button
                                                onClick={() => {
                                                    if (confirm('Are you sure you want to delete this product?')) {
                                                        // Execute the delete action here
                                                        // console.log('Product will be deleted');
                                                        router.delete(route('products.destroy', product.id), {
                                                            preserveScroll: true,
                                                        });
                                                    }
                                                }}
                                                className="ms-2 cursor-pointer rounded bg-red-600 p-2 text-white hover:opacity-80"
                                            >
                                                <Trash2 size={15} />
                                            </Button>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                // Jika panjang products = 0, paparkan mesej tiada produk
                                <tr>
                                    <td colSpan={7} className="text-md py-4 text-center font-bold text-red-700">
                                        No products found!
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </AppLayout>
    );
}
